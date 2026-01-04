<?php

declare(strict_types=1);

namespace App\Validators;

class SummaryValidator extends BaseValidator
{
    public function __construct($params)
    {
        parent::__construct($params);

        $this->messages = [
            'locale.required' => __('validators.summary.locale.required'),
            'locale.string'   => __('validators.summary.locale.string'),
            'text.required'   => __('validators.summary.text.required'),
            'text.array'      => __('validators.summary.text.array'),
        ];
    }

    public function setStoreRules(): self
    {
        $this->rules = [
            'locale' => 'required|string',
            'text'   => 'required|array',
        ];

        return $this;
    }
}
