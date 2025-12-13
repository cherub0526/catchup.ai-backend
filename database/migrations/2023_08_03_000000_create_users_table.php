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
        Schema::create('users', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->string('account')->index()->nullable()->comment('帳號');
            $table->string('name')->comment('姓名');
            $table->string('email')->nullable()->comment('電子郵件');
            $table->timestamp('email_verified_at')->nullable()->comment('驗證時間');
            $table->string('password')->comment('密碼');
            $table->string('social_type')->nullable()->comment('社群類型');

            $this->timestampsWithIndex($table, false, true);

            $table->comment('使用者');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
