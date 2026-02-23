<?php

declare(strict_types=1);

use Hypervel\Support\Facades\Schema;
use Hyperf\Database\Schema\Blueprint;
use Hypervel\Database\Migrations\Migration;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('provider_id')->index()->nullable()->after('login_type')->comment('社群 ID');
            $table->string('avatar')->nullable()->after('name')->comment('頭像');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('provider_id');
            $table->dropColumn('avatar');
        });
    }
};
