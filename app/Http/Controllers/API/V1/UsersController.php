<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\V1;

use Hypervel\Http\Request;
use App\Validators\UserValidator;
use App\Http\Resources\UserResource;
use Psr\Http\Message\ResponseInterface;
use App\Exceptions\InvalidRequestException;
use App\Http\Controllers\AbstractController;

class UsersController extends AbstractController
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

    /**
     * @throws InvalidRequestException
     */
    public function update(Request $request): ResponseInterface
    {
        $params = $request->only(['name', 'email']);

        $v = new UserValidator($params);
        $v->setUpdateRules();

        if (!$v->passes()) {
            throw new InvalidRequestException($v->errors()->toArray());
        }

        $request->user()->fill($params)->save();

        return response()->make(self::RESPONSE_OK);
    }
}
