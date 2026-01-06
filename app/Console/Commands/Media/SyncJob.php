<?php

declare(strict_types=1);

namespace App\Console\Commands\Media;

use App\Models\Media;
use App\Services\SQSService;
use Hypervel\Console\Command;

class SyncJob extends Command
{
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
            foreach ($medias as $media) {
                $sqs = new SQSService();
                switch ($media->status) {
                    case Media::STATUS_CREATED:
                        $media->update(['status' => Media::STATUS_PROGRESS]);
                        $sqs->push(SQSService::QUEUE_YOUTUBE_MP3_DOWNLOADER, [
                            'callback_url' => route(
                                'api.v1.webhook.youtube-mp3-downloader.store',
                                ['mediaId' => $media->id]
                            ),
                            'youtube_id' => $media->video_detail['yt:videoId'],
                        ]);
                        break;
                    case Media::STATUS_PROGRESS:
                        if (!isset($media->audio_detail['link'])) {
                            break;
                        }
                        $media->update(['status' => Media::STATUS_TRANSCRIBING]);
                        $sqs->push(SQSService::QUEUE_GROQ_TRANSCRIBE, [
                            'callback_url' => route(
                                'api.v1.webhook.groq.store',
                                ['mediaId' => $media->id]
                            ),
                            'data' => [
                                'source'      => $media->audio_detail['link'],
                                'destination' => sprintf('audios/%s.mp3', $media->id),
                            ],
                        ]);

                        break;
                    case Media::STATUS_TRANSCRIBED:
                        if (!$caption = $media->captions()->orderBy('primary', 'desc')->first()) {
                            break;
                        }
                        $media->update(['status' => Media::STATUS_SUMMARIZING]);
                        $sqs->push(SQSService::QUEUE_AI_SUMMARY, [
                            'callback_url' => route('api.v1.webhook.summaries.store', ['mediaId' => $media->id]),
                            'data'         => [
                                'locale' => $caption->locale,
                                'text'   => $caption->text,
                            ],
                        ]);
                        break;
                    default:
                        break;
                }
            }
        });
    }
}
