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
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id')->index()->comment('使用者 ID');
            $table->string('plan_id')->index()->comment('方案 ID');
            $table->string('price_id')->index()->comment('價格 ID');
            $table->string('payment_method')->default(\App\Models\Subscription::PAYMENT_METHOD_PADDLE)->comment(
                '付款方式'
            );
            $table->timestamp('start_date')->index()->comment('開始日');
            $table->timestamp('next_date')->index()->comment('下次扣款日');
            $table->timestamp('cancellation_date')->nullable()->index()->comment('取消日期');
            $table->timestamp('last_charged_date')->nullable()->index()->comment('最後付款日');
            $table->string('status')->default(\App\Models\Subscription::STATUS_TRIAL)->index()->comment('狀態');
            $table->text('note')->nullable()->comment('備注');

            $this->timestampsWithIndex($table, false, true);

            $table->comment('訂閱紀錄');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscriptions');
    }
};
