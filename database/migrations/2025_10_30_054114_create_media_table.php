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
        Schema::create('media', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('type')->index()->nullable()->comment('類型');
            $table->string('resource_id')->unique()->index()->comment('來源ID');
            $table->string('title')->nullable()->comment('標題');
            $table->text('description')->nullable()->comment('描述');
            $table->integer('duration')->default(0)->comment('時長');
            $table->mediumText('video_detail')->nullable()->comment('影片詳細資料');
            $table->mediumText('audio_detail')->nullable()->comment('音源詳細資料');
            $table->string('status')->default(\App\Models\Media::STATUS_CREATED)->index()->comment('狀態');
            $this->timestampsWithIndex($table, false, true);

            $table->comment('媒體資源');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('media');
    }
};
