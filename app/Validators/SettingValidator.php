<?php

declare(strict_types=1);

namespace App\Validators;

class SettingValidator extends BaseValidator
{
    public function __construct($params)
    {
        parent::__construct($params);

        $this->messages = [
            'ai.required'          => __('validators.settings.ai.required'),
            'ai.language.required' => __('validators.settings.ai.language.required'),
        ];
    }

    public function setUpdateRules(): self
    {
        $this->rules = [
            'ai'          => 'required',
            'ai.language' => 'required',
        ];
        return $this;
    }
}
