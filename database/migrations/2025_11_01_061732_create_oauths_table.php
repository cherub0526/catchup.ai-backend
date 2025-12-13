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
        Schema::create('oauths', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('provider')->index()->comment('服務提供者');
            $table->string('provider_id')->index()->comment('服務提供者 ID');
            $table->foreignUlid('user_id')->index()->comment('使用者 ID');
            $table->string('token', 1024)->comment('存取憑證');
            $table->string('refresh_token', 1024)->nullable()->comment('重新整理憑證');
            $table->integer('expires_in')->nullable()->comment('過期時間（秒）');
            $table->text('data')->nullable()->comment('資料');

            $this->timestampsWithIndex($table, false, false);
            $table->comment('OAuth 認證資料');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('oauths');
    }
};
