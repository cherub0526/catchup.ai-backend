<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Plan;
use App\Models\User;
use App\Models\Price;
use App\Models\Paddle;
use Hypervel\Support\Str;
use Hypervel\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Paddle>
 */
class PaddleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'foreign_id'   => strtolower(Str::ulid()->toString()),
            'foreign_type' => fake()->randomElement([
                Plan::class,
                Price::class,
                User::class,
            ]),
            'paddle_id'     => Str::ulid(),
            'paddle_detail' => [],
        ];
    }
}
