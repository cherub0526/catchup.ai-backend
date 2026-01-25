<?php

declare(strict_types=1);

use App\Utils\BaseMigration;
use Hypervel\Support\Facades\Schema;
use Hyperf\Database\Schema\Blueprint;

return new class extends BaseMigration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('images', function (Blueprint $table) {
            $table->ulid('id')->primary()->comment('ID');
            $table->foreignUlid('foreign_id')->index()->comment('外鍵 ID');
            $table->string('foreign_type')->index()->comment('外鍵類型');
            $table->string('filename', 1024)->comment('檔案名稱');
            $table->string('path', 1024)->nullable()->comment('圖片路徑');

            $this->timestampsWithIndex($table, false, true);

            $table->comment('圖片');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('images');
    }
};
