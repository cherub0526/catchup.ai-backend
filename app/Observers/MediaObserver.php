<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\Media;
use App\Jobs\Media\InfoJob;

class MediaObserver
{
    /**
     * Handle the Media "creating" event.
     */
    public function created(Media $media): void
    {
        if ($media->status === Media::STATUS_CREATED) {
            InfoJob::dispatch($media)->onQueue('media.information')->delay(now()->addSeconds(5));
        }
    }

    /**
     * Handle the Media "updated" event.
     */
    public function updated(Media $media): void
    {
        if ($media->status === Media::STATUS_PROGRESS && !count(array_keys($media->audio_detail)) > 0) {
            InfoJob::dispatch($media)->onQueue('caption:sync')->delay(now()->addSeconds(5));
        }

        if ($media->status === Media::STATUS_TRANSCRIBED && $media->captions()->exists()) {
            // TODO. 對內容進行總結.
            echo 'TODO. 對內容進行總結.';
        }
    }
}
