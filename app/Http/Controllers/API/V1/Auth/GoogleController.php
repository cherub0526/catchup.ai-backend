<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\V1\Auth;

use App\Models\User;
use Hypervel\Http\Request;
use App\Validators\GoogleValidator;
use Psr\Http\Message\ResponseInterface;
use App\Exceptions\InvalidRequestException;

class GoogleController
{
    public function store(Request $request)
    {
        $params = $request->only(['access_token', 'avatar_url', 'email', 'name', 'provider_id']);

        $v = new GoogleValidator($params);
        $v->setStoreRules();

        if (!$v->passes()) {
            throw new InvalidRequestException($v->errors()->toArray());
        }

        $user = User::query()
            ->where('social_type', User::SOCIAL_TYPE_GOOGLE)
            ->where('provider_id', $params['provider_id'])
            ->first();

        if (!$user) {
            $user = User::create([
                'account'     => $params['provider_id'],
                'password'    => bcrypt($params['provider_id']),
                'name'        => $params['name'],
                'email'       => $params['email'],
                'social_type' => User::SOCIAL_TYPE_GOOGLE,
                'provider_id' => $params['provider_id'],
                'avatar'      => $params['avatar_url'],
            ]);
        }

        $token = $this->guard()->login($user);

        return $this->responseAccessToken($token, 201);
    }

    private function guard()
    {
        return auth('jwt');
    }

    private function responseAccessToken(string $token, int $statusCode = 200): ResponseInterface
    {
        return response()->json([
            'access_token' => $token,
            'token_type'   => 'bearer',
            'expires_in'   => config('jwt.ttl') * 60,
        ], $statusCode);
    }
}
