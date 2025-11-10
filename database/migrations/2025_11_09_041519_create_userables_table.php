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
        Schema::create('userables', function (Blueprint $table) {
            $table->bigInteger('user_id')->nullable()->index()->comment('使用者 ID');
            $table->bigInteger('rss_id')->nullable()->index()->comment('RSS ID');
            $table->bigInteger('media_id')->nullable()->index()->comment('媒體 ID');
            $this->timestampsWithIndex($table, false, false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('userables');
    }
};
