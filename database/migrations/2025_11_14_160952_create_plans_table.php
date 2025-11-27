<?php

declare(strict_types=1);

use Hyperf\Database\Schema\Blueprint;
use Hyperf\Database\Schema\Schema;

return new class extends \App\Utils\BaseMigration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('plans', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->string('title')->comment('方案名稱');
            $table->text('description')->nullable()->comment('方案描述');
            $table->unsignedInteger('channel_limit')->default(0)->comment('頻道數量限制，0表示不限制');
            $table->unsignedInteger('video_limit')->default(0)->comment('影片數量限制，0表示不限制');
            $table->unsignedBigInteger('chat_limit')->default(0)->comment('聊天次數限制，0表示不限制');

            $table->unsignedInteger('sort')->default(0)->comment('排序');
            $table->string('status')->default(\App\Models\Plan::STATUS_ACTIVE)->comment('狀態');
            $this->timestampsWithIndex($table, false, true);

            $table->comment('訂閱方案表');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plans');
    }
};
