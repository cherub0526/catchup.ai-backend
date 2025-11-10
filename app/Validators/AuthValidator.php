<?php

declare(strict_types=1);

namespace App\Validators;

class AuthValidator extends BaseValidator
{
    public function __construct(array $params)
    {
        parent::__construct($params);

        $this->messages = [
            'account.required' => 'Account is required.',
            'account.string' => 'Account must be a string.',
            'account.min' => 'Account must be at least 6 characters.',
            'account.max' => 'Account must not exceed 255 characters.',
            'account.unique' => 'Account already exists.',
            'email.required' => 'Email is required.',
            'email.email' => 'Email format is invalid.',
            'email.max' => 'Email must not exceed 255 characters.',
            'password.required' => 'Password is required.',
            'password.string' => 'Password must be a string.',
            'password.min' => 'Password must be at least 8 characters.',
            'password.confirmed' => 'Password confirmation does not match.',
        ];
    }

    public function setStoreRules(): self
    {
        $this->rules = [
            'account' => 'required|min:6|max:255',
            'password' => 'required|string|min:8',
        ];

        return $this;
    }

    public function setRegisterRules(): self
    {
        $this->rules = [
            'account' => 'required|string|min:6|max:255|unique:users,account',
            'email' => 'required|email|max:255',
            'password' => 'required|string|min:8|confirmed',
        ];

        return $this;
    }
}
