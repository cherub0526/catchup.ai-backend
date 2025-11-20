<?php

declare(strict_types=1);

namespace App\Validators;

class PaddleTransactionValidator extends BaseValidator
{
    public function __construct($params)
    {
        parent::__construct($params);
    }

    public function setStoreRules(): self
    {
        $this->rules = [
            'event_id' => 'required',
            'event_type' => 'required|in:transaction.completed',
            'occurred_at' => 'required',
            'notification_id' => 'required',
            'data' => 'required',
            'data.id' => 'required',
        ];

        return $this;
    }
}
