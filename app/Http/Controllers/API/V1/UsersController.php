<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\V1;

use App\Http\Resources\UserResource;
use Hypervel\Http\Request;

class UsersController
{
    public function index(Request $request): UserResource
    {
        return new UserResource($request->user());
    }

    public function store(Request $request): UserResource
    {
        $params = $request->only(['name', 'email']);

        $user = $request->user();
        $user->fill($params)->save();

        return new UserResource($user);
    }
}
