<?php

declare(strict_types=1);

namespace App\Validators;

class OauthValidator extends BaseValidator
{
    public function __construct(array $params)
    {
        parent::__construct($params);

        $this->messages = [
            'provider.required' => __('validators.oauth.provider.required'),
            'provider.in' => __('validators.oauth.provider.in'),
            'code.required' => __('validators.oauth.code.required'),
        ];
    }

    public function setStoreRules(): self
    {
        $this->rules = [
            'provider' => 'required|in:' . implode(',', array_keys(\App\Models\Oauth::$providerMaps)),
            'code' => 'required',
        ];

        return $this;
    }
}
