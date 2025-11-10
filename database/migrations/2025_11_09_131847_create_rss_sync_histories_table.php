<?php

declare(strict_types=1);

use Hyperf\Database\Migrations\Migration;
use Hyperf\Database\Schema\Blueprint;
use Hyperf\Database\Schema\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('rss_sync_histories', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('rss_id')->index()->comment('RSS ID');
            $table->timestamp('synced_at')->index()->comment('同步時間');
            $table->string('status')->index()->comment('同步狀態');

            $table->comment('RSS 同步歷史紀錄表');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rss_sync_histories');
    }
};
