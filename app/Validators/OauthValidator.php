<?php

declare(strict_types=1);

namespace App\Validators;

class OauthValidator extends BaseValidator
{
    public function __construct(array $params)
    {
        parent::__construct($params);

        $this->messages = [
            'provider.required' => 'The provider field is required.',
            'provider.in' => 'The selected provider is invalid.',
            'code.required' => 'The code field is required.',
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
