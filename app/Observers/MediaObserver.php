<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\Media;
use App\Jobs\Media\InfoJob;
use App\Jobs\Media\CaptionJob;
use App\Jobs\Media\SummaryJob;

class MediaObserver
{
    /**
     * Handle the Media "updated" event.
     */
    public function saved(Media $media): void
    {
        if ($media->status === Media::STATUS_CREATED && ($media->wasRecentlyCreated || $media->wasChanged('status'))) {
            InfoJob::dispatch($media);
        }

        if ($media->status === Media::STATUS_PROGRESS && $media->wasChanged('status') && count(array_keys($media->audio_detail)) > 0) {
            CaptionJob::dispatch($media);
        }

        if ($media->status === Media::STATUS_TRANSCRIBED && $media->wasChanged('status') && $media->captions()->exists()) {
            SummaryJob::dispatch($media);
        }
    }
}
