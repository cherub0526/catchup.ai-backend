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
        Schema::create('captions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('media_id')->index()->nullable()->comment('資源 ID');
            $table->string('locale')->default(\App\Models\Caption::LOCAL_ZH_TW)->index()->comment('語系');
            $table->boolean('primary')->default(true)->index()->comment('主要');
            $table->mediumText('text')->nullable()->comment('文字');
            $table->mediumText('segments')->nullable()->comment('字幕段落');
            $table->mediumText('word_segments')->nullable()->comment('秒數字幕段落');
            $this->timestampsWithIndex($table, false, true);

            $table->comment('字幕');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('captions');
    }
};
