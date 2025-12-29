<?php

declare(strict_types=1);

namespace App\Jobs\Media;

use Exception;
use App\Models\Media;
use App\Services\SQSService;
use Hypervel\Queue\Queueable;
use App\Services\StorageService;
use Hypervel\Support\Facades\Http;
use Hypervel\Queue\Contracts\ShouldQueue;
use App\Exceptions\InvalidRequestException;

class CaptionJob implements ShouldQueue
{
    use Queueable;

    protected Media $media;

    /**
     * Create a new job instance.
     */
    public function __construct(Media $media)
    {
        $this->media = $media;

        $this->queue = 'media.caption';
    }

    /**
     * Execute the job.
     * @throws Exception
     */
    public function handle(): void
    {
        $this->media->fill(['status' => Media::STATUS_TRANSCRIBING])->save();

        $detail = $this->media->audio_detail;

        $subtitles = $detail['subtitles'] ?? [];

        $existCaptions = $this->media->captions()->get(['media_id', 'locale']);
        $existLocales = $existCaptions->pluck('locale')->toArray();

        // 如果有預設字幕就抓取，沒有的話就使用 Groq 服務快速生成字幕
        if (false && $subtitles && !empty($subtitles['items'])) {
            foreach ($subtitles['items'] as $caption) {
                if (in_array($caption['code'], $existLocales)) {
                    continue;
                }
                $xml = simplexml_load_file($caption['url']);
                $jsonArray = [];
                $id = 0;

                foreach ($xml->text as $node) {
                    $start = (float) $node['start'];
                    $dur = (float) $node['dur'];

                    $text = trim((string) $node);

                    $item = [
                        'id'          => $id,
                        'seek'        => 0,
                        'start'       => $start,
                        'end'         => $start + $dur,
                        'text'        => $text,
                        'temperature' => 0,
                    ];

                    $jsonArray[] = $item;
                    ++$id;
                }

                $this->media->captions()->create([
                    'locale'   => $caption['code'],
                    'primary'  => 0,
                    'text'     => collect($jsonArray)->map(fn ($line) => $line['text'])->join(' '),
                    'segments' => $jsonArray,
                ]);
            }

            $this->media->fill(['status' => Media::STATUS_TRANSCRIBED])->save();
        } else {
            $audios = collect($detail['audios']['items'])
                ->filter(function ($value) {
                    $size = $value['size'] / 1024 / 1024;

                    return $size <= 95 && $value['extension'] === 'weba';
                })
                ->sortBy('size');

            if ($audios->isEmpty()) {
                return;
            }

            $audioUrl = $audios->first()['url'];
            $tempDir = storage_path('temp');
            if (!is_dir($tempDir)) {
                mkdir($tempDir, 0755, true);
            }
            $localAudioPath = $tempDir . '/' . uniqid('audio_', true) . '.webm';

            $this->download($audioUrl, $localAudioPath);

            $storage = new StorageService();
            $destination = 'audios/' . $this->media->id . '.webm';
            $storage->upload($localAudioPath, $destination);

            // Clean up the temporary file
            if (file_exists($localAudioPath)) {
                unlink($localAudioPath);
            }

            $tempUrl = $storage->getTemporaryUrl($destination, now()->addHours(24));

            $sqs = new SQSService();
            $sqs->push('GroqTranscribe', [
                'callback_url' => route('api.v1.webhook.groq.store', ['mediaId' => $this->media->id]),
                'transcribe'   => [
                    'url'                     => $tempUrl,
                    'model'                   => 'whisper-large-v3-turbo',
                    'response_format'         => 'verbose_json',
                    'temperature'             => 0,
                    'timestamp_granularities' => ['segment', 'word'],
                ],
            ]);
        }
    }

    /**
     * Downloads a file from a URL and saves it to a local path using an async-friendly HTTP client.
     *
     * @param string $url the URL of the file to download
     * @param string $savePath the local path to save the file to
     * @return string the saved file path on success
     * @throws Exception if the download fails
     */
    public function download(string $url, string $savePath): string
    {
        $response = Http::sink($savePath)->withHeaders([
            'User-Agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36',
            'Accept'     => '*/*',
            'range'      => 'bytes=0-',
        ])->timeout(600)->get($url);

        if (!$response->successful()) {
            throw new InvalidRequestException(['file' => 'download failed']);
        }

        return $savePath;
    }
}
