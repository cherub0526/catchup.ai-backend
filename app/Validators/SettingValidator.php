<?php

declare(strict_types=1);

namespace App\Validators;

use App\Utils\Const\ISO6391;

class SettingValidator extends BaseValidator
{
    public function __construct($params)
    {
        parent::__construct($params);

        $this->messages = [
            'ai.required'          => __('validators.settings.ai.required'),
            'ai.language.required' => __('validators.settings.ai.language.required'),
            'ai.language.in'       => __('validators.settings.ai.language.in'),
        ];
    }

    public function setUpdateRules(): self
    {
        $this->rules = [
            'ai'          => 'required',
            'ai.language' => 'required|in:' . implode(',', array_values(ISO6391::LANGUAGES)),
        ];
        return $this;
    }
}
