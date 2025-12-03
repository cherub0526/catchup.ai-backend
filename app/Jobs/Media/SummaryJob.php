<?php

declare(strict_types=1);

namespace App\Jobs\Media;

use App\Models\Media;
use Hypervel\Queue\Queueable;
use Hypervel\Queue\Contracts\ShouldQueue;

class SummaryJob implements ShouldQueue
{
    use Queueable;

    protected Media $media;

    /**
     * Create a new job instance.
     */
    public function __construct(Media $media)
    {
        $this->media = $media;

        $this->queue = 'media.summary';
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
    }
}
