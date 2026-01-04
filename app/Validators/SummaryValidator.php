<?php

declare(strict_types=1);

namespace App\Validators;

class SummaryValidator extends BaseValidator
{
    public function __construct($params)
    {
        parent::__construct($params);

        $this->messages = [
            'locale.required'                       => __('validators.summary.locale.required'),
            'locale.string'                         => __('validators.summary.locale.string'),
            'text.required'                         => __('validators.summary.text.required'),
            'text.array'                            => __('validators.summary.text.array'),
            'text.short_summary.required'           => __('validators.summary.text.short_summary.required'),
            'text.short_summary.string'             => __('validators.summary.text.short_summary.string'),
            'text.long_summary.required'            => __('validators.summary.text.long_summary.required'),
            'text.long_summary.array'               => __('validators.summary.text.long_summary.array'),
            'text.long_summary.content.required'    => __('validators.summary.text.long_summary.content.required'),
            'text.long_summary.content.string'      => __('validators.summary.text.long_summary.content.string'),
            'text.long_summary.key_points.required' => __('validators.summary.text.long_summary.key_points.required'),
            'text.long_summary.key_points.array'    => __('validators.summary.text.long_summary.key_points.array'),
            'text.long_summary.keywords.required'   => __('validators.summary.text.long_summary.keywords.required'),
            'text.long_summary.keywords.array'      => __('validators.summary.text.long_summary.keywords.array'),
        ];
    }

    public function setStoreRules(): self
    {
        $this->rules = [
            'locale'                       => 'required|string',
            'text'                         => 'required|array',
            'text.short_summary'           => 'required|string',
            'text.long_summary'            => 'required|array',
            'text.long_summary.content'    => 'required|string',
            'text.long_summary.key_points' => 'required|array',
            'text.long_summary.keywords'   => 'required|array',
        ];

        return $this;
    }
}
