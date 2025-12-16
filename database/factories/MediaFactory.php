<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Media;
use Hypervel\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Media>
 */
class MediaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'type'         => fake()->randomElement(array_keys(Media::$typeMaps)),
            'resource_id'  => fake()->unique()->regexify('[a-zA-Z0-9_-]{11}'),
            'title'        => fake()->sentence(),
            'description'  => fake()->paragraph(),
            'duration'     => fake()->numberBetween(60, 3600),
            'thumbnail'    => fake()->imageUrl(),
            'published_at' => fake()->dateTimeThisYear(),
            'status'       => fake()->randomElement(array_keys(Media::$statusMap)),
            'video_detail' => [],
            'audio_detail' => [],
        ];
    }
}
