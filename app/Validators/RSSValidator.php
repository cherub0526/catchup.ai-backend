<?php

declare(strict_types=1);

namespace App\Validators;

class RSSValidator extends BaseValidator
{
    public function __construct($params)
    {
        parent::__construct($params);

        $this->messages = [
            'type.required' => trans('Validators.RSS.type.required'),
            'type.string' => trans('Validators.RSS.type.string'),
            'type.in' => trans('Validators.RSS.type.in'),
            'url.required' => trans('Validators.RSS.url.required'),
        ];
    }

    public function setStoreRules(): self
    {
        $this->rules = [
            'type' => 'required|string|in:' . implode(',', array_keys(\App\Models\Rss::$typeMaps)),
            'url' => 'required',
        ];

        return $this;
    }

    public function setIndexRules(): self
    {
        $this->rules = [
            'type' => 'required|string|in:' . implode(',', array_keys(\App\Models\Rss::$typeMaps)),
        ];

        return $this;
    }
}
