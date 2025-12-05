<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\User;
use App\Services\PaddleClient;
use Paddle\SDK\Resources\Customers\Operations\CreateCustomer;
use Paddle\SDK\Resources\Customers\Operations\UpdateCustomer;

class UserObserver
{
    /**
     * Handle the User "created" event.
     */
    public function created(User $user): void
    {
        $paddle = new PaddleClient();

        $response = $paddle->customers()->create(
            new CreateCustomer(
                email: $user->email,
                name: $user->name
            )
        );

        $user->paddle()->create([
            'foreign_type'  => User::class,
            'paddle_id'     => $response->id,
            'paddle_detail' => $response,
        ]);
    }

    /**
     * Handle the User "updated" event.
     */
    public function updated(User $user): void
    {
        $paddle = new PaddleClient();

        if ($user->paddle()->exists()) {
            $paddle->customers()->update(
                $user->paddle->paddle_id,
                new UpdateCustomer(
                    email: $user->email,
                    name: $user->name
                )
            );
        }
    }

    /**
     * Handle the User "deleted" event.
     */
    public function deleted(User $user): void
    {
    }

    /**
     * Handle the User "restored" event.
     */
    public function restored(User $user): void
    {
    }

    /**
     * Handle the User "force deleted" event.
     */
    public function forceDeleted(User $user): void
    {
    }
}
