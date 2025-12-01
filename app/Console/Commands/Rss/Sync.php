<?php

declare(strict_types=1);

namespace App\Console\Commands\Rss;

use App\Models\Rss;
use App\Jobs\Rss\SyncJob;
use Hypervel\Console\Command;

class Sync extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected ?string $signature = 'rss:sync';

    /**
     * The console command description.
     */
    protected string $description = 'Sync RSS feeds';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $executed = [];
        Rss::query()->chunkById(100, function ($items) use (&$executed) {
            foreach ($items as $item) {
                if (in_array($item->url, $executed)) {
                    continue;
                }
                $this->info('Syncing RSS: ' . $item->title . ' (' . $item->url . ')');

                SyncJob::dispatch($item)->onQueue('rss.sync');
            }
        });
    }
}
