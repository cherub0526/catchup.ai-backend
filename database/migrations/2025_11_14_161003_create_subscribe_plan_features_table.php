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
        Schema::create('subscribe_plan_features', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('subscribe_plan_id')->comment('訂閱方案ID');
            $table->unsignedInteger('channel_limit')->default(0)->comment('頻道數量限制，0表示不限制');
            $table->unsignedInteger('video_limit')->default(0)->comment('影片數量限制，0表示不限制');
            $table->unsignedBigInteger('chat_limit')->default(0)->comment('聊天次數限制，0表示不限制');
            $this->timestampsWithIndex($table, false, true);

            $table->comment('訂閱方案功能表');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscribe_plan_features');
    }
};
