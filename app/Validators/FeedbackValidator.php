<?php

declare(strict_types=1);

namespace App\Validators;

class FeedbackValidator extends BaseValidator
{
    public function __construct($params)
    {
        parent::__construct($params);
    }

    public function setStoreRules(): self
    {
        $this->rules = [
            'content' => 'required|string',
        ];

        return $this;
    }
}
