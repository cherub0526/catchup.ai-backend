<?php

declare(strict_types=1);

namespace App\Validators;

use App\Models\Rss;

class RSSValidator extends BaseValidator
{
    public function __construct($params)
    {
        parent::__construct($params);

        $this->messages = [
            'type.required' => __('validators.rss.type.required'),
            'type.string'   => __('validators.rss.type.string'),
            'type.in'       => __('validators.rss.type.in'),
            'url.required'  => __('validators.rss.url.required'),
        ];
    }

    public function setStoreRules(): self
    {
        $this->rules = [
            'type' => 'required|string|in:' . implode(',', array_keys(Rss::$typeMaps)),
            'url'  => 'required',
        ];

        return $this;
    }

    public function setIndexRules(): self
    {
        $this->rules = [
            'type' => 'required|string|in:' . implode(',', array_keys(Rss::$typeMaps)),
        ];

        return $this;
    }
}
