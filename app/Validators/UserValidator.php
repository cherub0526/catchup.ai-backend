<?php

declare(strict_types=1);

namespace App\Validators;

class UserValidator extends BaseValidator
{
    public function __construct($params)
    {
        parent::__construct($params);
    }

    public function setUpdateRules(): self
    {
        $this->rules = [
            'name' => 'required|string',
            'email' => 'required|email',
        ];

        return $this;
    }
}
