<?php

declare(strict_types=1);

namespace App\Console\Commands\Media;

use App\Models\Media;
use App\Jobs\Media\InfoJob;
use Hypervel\Console\Command;
use App\Jobs\Media\CaptionJob;
use App\Jobs\Media\SummaryJob;

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
            $medias->loadMissing(['cations']);

            foreach ($medias as $media) {
                match ($media->status) {
                    Media::STATUS_CREATED  => InfoJob::dispatch($media),
                    Media::STATUS_PROGRESS => isset($media->audio_detail['download'])
                        ? CaptionJob::dispatch($media)
                        : null,
                    Media::STATUS_TRANSCRIBED => $media->captions->first()
                        ? SummaryJob::dispatch($media)
                        : null,
                    default => null,
                };
            }
        });
    }
}
