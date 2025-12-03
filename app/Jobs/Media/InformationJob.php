<?php

declare(strict_types=1);

namespace App\Jobs\Media;

use Exception;
use App\Models\Media;
use Hypervel\Queue\Queueable;
use Hypervel\Queue\Contracts\ShouldQueue;
use App\Services\RapidApi\YoutubeMediaDownloader;

class InformationJob implements ShouldQueue
{
    use Queueable;

    protected Media $media;

    /**
     * Create a new job instance.
     */
    public function __construct(Media $media)
    {
        $this->media = $media;

        $this->queue = 'media.information';
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $client = match ($this->media->type) {
            Media::TYPE_YOUTUBE => new YoutubeMediaDownloader()
        };

        $videoId = $this->media->video_detail['yt:videoId'];

        try {
            $response = $client->video()->setVideoId($videoId)->details();

            $this->media->fill([
                'status'       => Media::STATUS_PROGRESS,
                'audio_detail' => $response,
            ])->save();
        } catch (Exception $exception) {
            $this->media->fill(['status' => Media::STATUS_FAILED])->save();
        }
    }
}
