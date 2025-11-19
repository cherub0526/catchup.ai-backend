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
        Schema::create('paddles', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('foreign_id')->index()->comment('外鍵 ID');
            $table->string('foreign_type')->index()->comment('外鍵 Type');
            $table->string('paddle_id')->index()->nullable()->comment('Paddle ID');
            $table->text('paddle_detail')->nullable()->comment('Paddle Detail');

            $table->index(['foreign_id', 'foreign_type']);

            $this->timestampsWithIndex($table, false, false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('paddles');
    }
};
