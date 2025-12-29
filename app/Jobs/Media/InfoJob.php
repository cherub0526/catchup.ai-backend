<?php

declare(strict_types=1);

namespace App\Jobs\Media;

use Exception;
use App\Models\Media;
use Hypervel\Queue\Queueable;
use Hypervel\Queue\Contracts\ShouldQueue;
use App\Services\RapidApi\YoutubeMp3Downloader;

class InfoJob implements ShouldQueue
{
    use Queueable;

    protected Media $media;

    /**
     * Create a new job instance.
     */
    public function __construct(Media $media)
    {
        $this->media = $media;

        $this->queue = 'media.info';
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $client = match ($this->media->type) {
            Media::TYPE_YOUTUBE => new YoutubeMp3Downloader()
        };

        $videoId = $this->media->video_detail['yt:videoId'];

        try {
            $response = $client->send('GET', '/mp3', [
                'url' => sprintf('https://www.youtube.com/watch?v=%s', $videoId),
            ]);

            $this->media->fill([
                'status'       => Media::STATUS_PROGRESS,
                'audio_detail' => $response,
            ])->save();
        } catch (Exception $exception) {
            $this->media->fill(['status' => Media::STATUS_FAILED])->save();
        }
    }
}
