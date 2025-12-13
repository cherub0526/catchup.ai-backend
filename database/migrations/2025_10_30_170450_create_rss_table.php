<?php

declare(strict_types=1);

use App\Models\Rss;
use App\Utils\BaseMigration;
use Hyperf\Database\Schema\Schema;
use Hyperf\Database\Schema\Blueprint;

return new class extends BaseMigration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('rss', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->string('type')->default(Rss::TYPE_YOUTUBE)->index()->comment('類型');
            $table->string('title')->comment('標題');
            $table->string('url', 1024)->comment('網址');
            $table->text('comment')->nullable()->comment('備注');
            $this->timestampsWithIndex($table, false, true);

            $table->comment('RSS 訂閱');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rss');
    }
};
