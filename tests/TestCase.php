<?php

declare(strict_types=1);

namespace Tests;

use App\Models\User;
use Hypervel\Database\Eloquent\Model;
use Hypervel\Database\Eloquent\Collection;
use Hypervel\Foundation\Testing\TestCase as BaseTestCase;
use Hypervel\Foundation\Testing\Concerns\RunTestsInCoroutine;

abstract class TestCase extends BaseTestCase
{
    use RunTestsInCoroutine;

    public function fakeLogin(): Collection|Model|null
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        return $user;
    }
}
