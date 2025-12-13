<?php

declare(strict_types=1);

use App\Utils\BaseMigration;
use Hyperf\Database\Schema\Schema;
use Hyperf\Database\Schema\Blueprint;

return new class extends BaseMigration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('userables', function (Blueprint $table) {
            $table->foreignUlid('user_id')->nullable()->index()->comment('使用者 ID');
            $table->foreignUlid('rss_id')->nullable()->index()->comment('RSS ID');
            $table->foreignUlid('media_id')->nullable()->index()->comment('媒體 ID');
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
