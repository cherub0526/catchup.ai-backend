<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Plan;
use App\Models\Price;
use Hypervel\Database\Seeder;

class PlanPriceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $plans = [
            [
                'title'         => 'Free',
                'channel_limit' => 1,
                'video_limit'   => 3,
                'prices'        => [
                    [
                        'unit'  => Price::UNIT_MONTHLY,
                        'price' => 0,
                    ],
                    [
                        'unit'  => Price::UNIT_ANNUALLY,
                        'price' => 0,
                    ],
                ],
            ],
            [
                'title'         => 'Basic',
                'channel_limit' => 3,
                'video_limit'   => 50,
                'prices'        => [
                    [
                        'unit'  => Price::UNIT_MONTHLY,
                        'price' => 5,
                    ],
                    [
                        'unit'  => Price::UNIT_ANNUALLY,
                        'price' => 48,
                    ],
                ],
            ],
            [
                'title'         => 'Advance',
                'channel_limit' => 5,
                'video_limit'   => 100,
                'prices'        => [
                    [
                        'unit'  => Price::UNIT_MONTHLY,
                        'price' => 10,
                    ],
                    [
                        'unit'  => Price::UNIT_ANNUALLY,
                        'price' => 96,
                    ],
                ],
            ],
        ];

        foreach ($plans as $key => $plan) {
            $entity = Plan::create([
                'title'         => $plan['title'],
                'channel_limit' => $plan['channel_limit'],
                'video_limit'   => $plan['video_limit'],
                'sort'          => $key,
            ]);

            foreach ($plan['prices'] as $price) {
                $entity->prices()->create([
                    'unit'  => $price['unit'],
                    'price' => $price['price'],
                ]);
            }
        }
    }
}
