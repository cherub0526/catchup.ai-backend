<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\V1\Auth;

use App\Models\User;
use Hypervel\Http\Request;
use App\Mail\ResetPasswordMail;
use Hypervel\Support\Facades\URL;
use Hypervel\Support\Facades\Hash;
use Hypervel\Support\Facades\Mail;
use Psr\Http\Message\ResponseInterface;
use App\Exceptions\InvalidRequestException;
use App\Validators\ForgotPasswordValidator;
use App\Http\Controllers\AbstractController;

class ForgotPasswordController extends AbstractController
{
    /**
     * @throws InvalidRequestException
     */
    public function store(Request $request): ResponseInterface
    {
        $params = $request->only(['account']);

        $v = new ForgotPasswordValidator($params);
        $v->setStoreRules();

        if (!$v->passes()) {
            throw new InvalidRequestException($v->errors()->toArray());
        }

        if (!$user = User::where('account', $params['account'])->first()) {
            // To prevent user enumeration, we'll return a generic success response even if the user doesn't exist.
            throw new InvalidRequestException(['account' => 'Account not found.']);
        }

        $stringId = strval($user->id);

        // 生成一個唯一且加密安全的 Token
        $token = Hash::make($stringId);

        $minutes = 60;

        $resetUrl = URL::temporarySignedRoute(
            'api.v1.auth.forgot-password.update',
            now()->addMinutes($minutes),
            [
                'token' => $token, // 使用生成的唯一 Token
                'id'    => $stringId, // 傳遞 id 以便在重設頁面使用
            ],
            false
        );

        Mail::to($user->email)->send(new ResetPasswordMail($resetUrl, $minutes));

        return response()->json([
            'message' => __('passwords.sent'),
        ]);
    }

    /**
     * @throws InvalidRequestException
     */
    public function update(Request $request)
    {
        $params = $request->only(['expires', 'id', 'token', 'signature', 'password', 'password_confirmation']);

        $v = new ForgotPasswordValidator($params);
        $v->setUpdateRules();

        if (!$v->passes()) {
            throw new InvalidRequestException($v->errors()->toArray());
        }

        if (!Hash::check($params['id'], $params['token'])) {
            throw new InvalidRequestException(['token' => 'Invalid token.']);
        }

        $user = User::query()->find($params['id']);
        $user->fill(['password' => Hash::make(trim($params['password']))])->save();

        return $this->responseAccessToken(auth('jwt')->login($user));
    }

    private function responseAccessToken(string $token): ResponseInterface
    {
        return response()->json([
            'access_token' => $token,
            'token_type'   => 'bearer',
            'expires_in'   => config('jwt.ttl') * 60,
        ]);
    }
}
