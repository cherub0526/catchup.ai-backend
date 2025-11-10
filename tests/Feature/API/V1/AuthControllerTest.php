<?php

declare(strict_types=1);

namespace Tests\Feature\API\V1;

use App\Validators\AuthValidator;
use Hypervel\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * @internal
 * @coversNothing
 */
class AuthControllerTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
    }

    public function testStore()
    {
        $uri = route('api.v1.auth.store');

        $this->json('POST', $uri)->assertStatus(422)->assertJsonStructure([
            'messages' => [
                'account',
                'password',
            ],
        ]);

        $messages = (new AuthValidator([]))->getMessages();

        $params = [
            'account' => 'abc',
        ];
        $this->json('POST', $uri, $params)->assertStatus(422)->assertJsonPath(
            'messages.account',
            [$messages['account.min']]
        );

        $params['account'] = fake()->userName();
        $this->json('POST', $uri, $params)->assertStatus(422)->assertJsonPath(
            'messages.password',
            [$messages['password.required']]
        );
    }

    public function testRegister()
    {
        $uri = route('api.v1.auth.register');
        $this->json('POST', $uri)->assertStatus(422)->assertJsonStructure([
            'messages' => [
                'account',
                'email',
                'password',
            ],
        ]);
    }

    public function testLogout()
    {
        $uri = route('api.v1.auth.logout');
        $this->json('POST', $uri)->assertStatus(401);
    }
}
