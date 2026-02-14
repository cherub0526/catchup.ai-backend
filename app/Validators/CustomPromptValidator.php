<?php

declare(strict_types=1);

namespace App\Validators;

class CustomPromptValidator extends BaseValidator
{
    public function __construct($params)
    {
        parent::__construct($params);
    }

    public function setStoreRules(): self
    {
        $this->rules = [
            'prompt' => 'required',
        ];

        return $this;
    }
}
