<?php

declare(strict_types=1);

namespace App\Console\Commands\Media;

use App\Models\Media;
use App\Services\SQSService;
use Hypervel\Console\Command;

class SyncJob extends Command
{
    public const string QUEUE_YOUTUBE_MP3_DOWNLOADER = 'YoutubeMp3Downloader';
    public const string QUEUE_GROQ_TRANSCRIBE = 'GroqTranscribe';

    /**
     * The name and signature of the console command.
     */
    protected ?string $signature = 'media:sync-job';

    /**
     * The console command description.
     */
    protected string $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Media::query()->whereIn('status', [
            Media::STATUS_CREATED,
            Media::STATUS_PROGRESS,
            Media::STATUS_TRANSCRIBED,
        ])->chunkById(100, function ($medias) {
            $medias->loadMissing(['captions']);
            foreach ($medias as $media) {
                $sqs = new SQSService();

                switch ($media->status) {
                    case Media::STATUS_CREATED:
                        $sqs->push(self::QUEUE_YOUTUBE_MP3_DOWNLOADER, [
                            'callback_url' => route(
                                'api.v1.webhook.youtube-mp3-downloader.store',
                                ['mediaId' => $media->id]
                            ),
                            'youtube_id' => $media->video_detail['yt:videoId'],
                        ]);
                        break;
                    case Media::STATUS_PROGRESS:
                        $sqs->push(self::QUEUE_GROQ_TRANSCRIBE, [
                        ]);
                        // no break
                    default:
                        break;
                }
            }
        });
    }
}
