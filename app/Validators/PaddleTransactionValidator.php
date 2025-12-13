<?php

declare(strict_types=1);

namespace App\Validators;

class PaddleTransactionValidator extends BaseValidator
{
    public function __construct($params)
    {
        parent::__construct($params);

        $this->messages = [
            'event_id.required'        => __('validators.paddle.event_id.required'),
            'event_type.required'      => __('validators.paddle.event_type.required'),
            'event_type.in'            => __('validators.paddle.event_type.in'),
            'occurred_at.required'     => __('validators.paddle.occurred_at.required'),
            'notification_id.required' => __('validators.paddle.notification_id.required'),
            'data.required'            => __('validators.paddle.data.required'),
            'data.id.required'         => __('validators.paddle.data.id.required'),
        ];
    }

    public function setStoreRules(): self
    {
        $this->rules = [
            'event_id'        => 'required',
            'event_type'      => 'required|in:transaction.completed',
            'occurred_at'     => 'required',
            'notification_id' => 'required',
            'data'            => 'required',
            'data.id'         => 'required',
        ];

        return $this;
    }
}
