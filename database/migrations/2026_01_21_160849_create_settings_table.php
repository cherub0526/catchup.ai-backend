<?php

declare(strict_types=1);

use App\Models\User;
use App\Utils\BaseMigration;
use App\Utils\Const\ISO6391;
use Hypervel\Support\Facades\Schema;
use Hyperf\Database\Schema\Blueprint;

return new class extends BaseMigration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignUlid('user_id')->index()->comment('使用者 ID');
            $table->json('data')->comment('設定');
            $this->timestampsWithIndex($table, false);

            $table->comment('使用者設定');
        });

        User::withTrashed()->chunkById(100, function ($users) {
            $insertData = [];

            foreach ($users as $user) {
                $insertData[] = [
                    'user_id' => $user->id,
                    'data'    => json_encode(
                        [
                            'locale' => ISO6391::getCodeByName('English'),
                            'ai'     => [
                                'language' => ISO6391::getCodeByName('English'),
                            ],
                        ]
                    ),
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            DB::table('settings')->insert($insertData);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
