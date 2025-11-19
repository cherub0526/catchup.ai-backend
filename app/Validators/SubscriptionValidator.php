<?php

declare(strict_types=1);

namespace App\Validators;

class SubscriptionValidator extends BaseValidator
{
    public function __construct($params)
    {
        parent::__construct($params);

        $this->messages = [
            'transaction_id.required' => trans('validation.subscription.transaction_id_required'),
            'transaction_id.string' => trans('validation.subscription.transaction_id_string'),
        ];
    }

    public function setStoreRules(): self
    {
        $this->rules = [
            'transaction_id' => 'required|string',
        ];

        return $this;
    }
}
