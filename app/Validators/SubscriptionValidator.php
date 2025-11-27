<?php

declare(strict_types=1);

namespace App\Validators;

class SubscriptionValidator extends BaseValidator
{
    public function __construct($params)
    {
        parent::__construct($params);

        $this->messages = [
            'planId.required' => trans(''),
            'planId.string' => trans(''),
            'priceId.required' => trans(''),
            'priceId.required' => trans(''),
        ];
    }

    public function setStoreRules(): self
    {
        $this->rules = [
            'planId' => 'required|string',
            'priceId' => 'required|string',
        ];

        return $this;
    }
}
