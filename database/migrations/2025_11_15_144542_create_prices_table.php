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
        Schema::create('prices', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('plan_id')->index()->comment('訂閱方案ID');
            $table->string('paddle_price_id')->nullable()->comment('Paddle 價格ID');
            $table->string('unit')->comment('計費單位，monthly-月付，quarterly-季付，yearly-年付');
            $table->decimal('price', 8, 2)->default(0.00)->comment('價格');
            $this->timestampsWithIndex($table, false, true);

            $table->comment('訂閱方案價格表');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prices');
    }
};
