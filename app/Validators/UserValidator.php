<?php

declare(strict_types=1);

namespace App\Validators;

class UserValidator extends BaseValidator
{
    public function __construct($params)
    {
        parent::__construct($params);

        $this->messages = [
            'name.required'  => __('validators.user.name.required'),
            'name.string'    => __('validators.user.name.string'),
            'email.required' => __('validators.user.email.required'),
            'email.email'    => __('validators.user.email.email'),
        ];
    }

    public function setUpdateRules(): self
    {
        $this->rules = [
            'name'  => 'required|string',
            'email' => 'required|email',
        ];

        return $this;
    }
}
