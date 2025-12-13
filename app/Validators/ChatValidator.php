<?php

declare(strict_types=1);

namespace App\Validators;

class ChatValidator extends BaseValidator
{
    public function __construct($params)
    {
        parent::__construct($params);

        $this->messages = [
            'messages.required'           => __('validators.chat.messages.required'),
            'messages.array'              => __('validators.chat.messages.array'),
            'messages.min'                => __('validators.chat.messages.min'),
            'messages.*.role.required'    => __('validators.chat.messages.role.required'),
            'messages.*.role.string'      => __('validators.chat.messages.role.string'),
            'messages.*.role.in'          => __('validators.chat.messages.role.in'),
            'messages.*.content.required' => __('validators.chat.messages.content.required'),
            'messages.*.content.string'   => __('validators.chat.messages.content.string'),
        ];
    }

    public function setStoreRules(): self
    {
        $this->rules = [
            'messages'           => 'required|array|min:1',
            'messages.*.role'    => 'required|string|in:user,assistant,system',
            'messages.*.content' => 'required|string',
        ];

        return $this;
    }
}
