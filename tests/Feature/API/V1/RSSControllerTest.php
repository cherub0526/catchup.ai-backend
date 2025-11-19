<?php

declare(strict_types=1);

namespace Tests\Feature\API\V1;

use App\Models\Rss;
use Hypervel\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * @internal
 * @coversNothing
 */
class RSSControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testStore()
    {
        $uri = route('api.v1.rss.store');

        $this->json('POST', $uri)->assertStatus(401);

        $user = $this->fakeLogin();

        $this->json('POST', $uri)->assertStatus(422)
            ->assertJsonStructure(['messages' => ['type', 'url']]);

        $params = ['type' => Rss::TYPE_YOUTUBE];
        $this->json('POST', $uri, $params)->assertStatus(422)
            ->assertJsonStructure(['messages' => ['url']]);

        $params['url'] = 'UCAuUUnT6oDeKwE6v1NGQxug';
        $this->json('POST', $uri, $params)->assertStatus(201);

        $this->assertDatabaseHas('rss', [
            'type' => Rss::TYPE_YOUTUBE,
            'url' => 'https://www.youtube.com/feeds/videos.xml?channel_id=UCAuUUnT6oDeKwE6v1NGQxug',
        ]);

        $this->assertDatabaseHas('userables', [
            'user_id' => $user->id,
            'rss_id' => 1,
        ]);
    }
}
