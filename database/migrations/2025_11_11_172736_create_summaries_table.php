<?php

declare(strict_types=1);

use App\Models\Summary;
use App\Utils\BaseMigration;
use Hyperf\Database\Schema\Schema;
use Hyperf\Database\Schema\Blueprint;

return new class extends BaseMigration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('summaries', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('media_id')->index()->comment('媒體ID');
            $table->string('locale')->index()->comment('語言');
            $table->text('text')->nullable()->comment('摘要文字');
            $table->string('status')->default(Summary::STATUS_CREATED)->index()->comment('狀態');
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
