<?php

declare(strict_types=1);

use App\Models\Feedback;
use App\Utils\BaseMigration;
use Hypervel\Support\Facades\Schema;
use Hyperf\Database\Schema\Blueprint;

return new class extends BaseMigration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('feedbacks', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->text('content')->comment('內容');
            $table->string('status')->default(Feedback::STATUS_CREATED)->comment('狀態');
            $this->timestampsWithIndex($table, false, true);

            $table->comment('意見回饋');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('feedbacks');
    }
};
