<?php

declare(strict_types=1);

use Hyperf\Database\Schema\Blueprint;
use Hypervel\Support\Facades\Schema;

return new class extends \App\Utils\BaseMigration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name')->comment('姓名');
            $table->string('email')->unique()->comment('電子郵件');
            $table->timestamp('email_verified_at')->nullable()->comment('驗證時間');
            $table->string('password')->comment('密碼');
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
