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
        Schema::create('summaries', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('media_id')->index()->comment('媒體ID');
            $table->string('locale')->index()->comment('語言');
            $table->text('text')->nullable()->comment('摘要文字');
            $table->string('status')->default(\App\Models\Summary::STATUS_CREATED)->index()->comment('狀態');
            $this->timestampsWithIndex($table, false, true);

            $table->comment('摘要');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('summaries');
    }
};
