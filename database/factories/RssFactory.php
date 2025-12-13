<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Rss;
use Hypervel\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Rss>
 */
class RssFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'type'    => fake()->randomElement(array_values(Rss::$typeMaps)),
            'title'   => $this->faker->sentence(),
            'url'     => $this->faker->url(),
            'comment' => $this->faker->paragraph(),
        ];
    }

    /**
     * Indicate that the RSS feed is a YouTube channel.
     *
     * @return Factory
     */
    public function youtube()
    {
        return $this->state(function (array $attributes) {
            $channelId = 'UC' . $this->faker->regexify('[A-Za-z0-9_-]{22}');
            return [
                'type' => Rss::TYPE_YOUTUBE,
                'url'  => 'https://www.youtube.com/feeds/videos.xml?channel_id=' . $channelId,
            ];
        });
    }
}
