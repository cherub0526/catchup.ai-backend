<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\V1;

use App\Models\User;
use Hypervel\Http\Request;
use App\Validators\AuthValidator;
use Psr\Http\Message\ResponseInterface;
use App\Exceptions\InvalidRequestException;
use App\Http\Controllers\AbstractController;

class AuthController extends AbstractController
{
    /**
     * @throws InvalidRequestException
     */
    public function store(Request $request): ResponseInterface
    {
        $params = $request->only(['account', 'password']);

        $v = new AuthValidator($params);
        $v->setStoreRules();

        if (!$v->passes()) {
            throw new InvalidRequestException($v->errors()->toArray());
        }

        if (!$this->guard()->attempt($params)) {
            throw new InvalidRequestException(['password' => [__('validators.controllers.auth.invalid_credentials')]]);
        }

        return $this->responseAccessToken(auth()->login($request->user()));
    }

    /**
     * @throws InvalidRequestException
     */
    public function register(Request $request): ResponseInterface
    {
        $params = $request->only(['account', 'email', 'password', 'password_confirmation']);

        $v = new AuthValidator($params);
        $v->setRegisterRules();

        if (!$v->passes()) {
            throw new InvalidRequestException($v->errors()->toArray());
        }

        $user = User::query()
            ->where('account', $params['account'])
            ->where('social_type', User::SOCIAL_TYPE_LOCAL)
            ->first();

        if (!$user) {
            $user = User::create([
                'account'     => $params['account'],
                'name'        => $params['account'],
                'email'       => $params['email'],
                'password'    => bcrypt($params['password']),
                'social_type' => User::SOCIAL_TYPE_LOCAL,
            ]);
        }

        $token = $this->guard()->login($user);

        return $this->responseAccessToken($token, 201);
    }

    public function refresh(Request $request): ResponseInterface
    {
        $token = $this->guard()->refresh();

        return $this->responseAccessToken($token);
    }

    public function logout(Request $request): ResponseInterface
    {
        return response()->make(self::RESPONSE_OK, 200);
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
