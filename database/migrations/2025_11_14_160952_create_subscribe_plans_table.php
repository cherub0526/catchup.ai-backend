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
        Schema::create('subscribe_plans', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title')->comment('方案名稱');
            $table->text('description')->nullable()->comment('方案描述');
            $table->unsignedInteger('monthly_price')->default(0)->comment('月付價格');
            $table->unsignedInteger('yearly_price')->default(0)->comment('年付價格');
            $table->unsignedInteger('sort')->default(0)->comment('排序');
            $table->string('status')->default(\App\Models\SubscribePlan::STATUS_ACTIVE)->comment('狀態');

            $this->timestampsWithIndex($table, false, true);

            $table->comment('訂閱方案表');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscribe_plans');
    }
};
