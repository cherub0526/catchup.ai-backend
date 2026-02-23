<?php

declare(strict_types=1);

namespace App\Validators;

class GoogleValidator extends BaseValidator
{
    public function __construct($params)
    {
        parent::__construct($params);
    }

    public function setStoreRules(): self
    {
        $this->rules = [
            'access_token' => 'required',
            'avatar_url'   => 'required',
            'email'        => 'required|email',
            'name'         => 'required',
        ];

        return $this;
    }
}
