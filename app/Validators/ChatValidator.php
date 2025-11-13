<?php

declare(strict_types=1);

namespace App\Validators;

class ChatValidator extends BaseValidator
{
    public function __construct($params)
    {
        parent::__construct($params);

        $this->messages = [
            'messages.required' => trans('Validators.Chat.messages.required'),
            'messages.array' => trans('Validators.Chat.messages.array'),
            'messages.min' => trans('Validators.Chat.messages.min'),
            'messages.*.role.required' => trans('Validators.Chat.messages.role.required'),
            'messages.*.role.string' => trans('Validators.Chat.messages.role.string'),
            'messages.*.role.in' => trans('Validators.Chat.messages.role.in'),
            'messages.*.content.required' => trans('Validators.Chat.messages.content.required'),
            'messages.*.content.string' => trans('Validators.Chat.messages.content.string'),
        ];
    }

    public function setStoreRules(): self
    {
        $this->rules = [
            'messages' => 'required|array|min:1',
            'messages.*.role' => 'required|string|in:user,assistant,system',
            'messages.*.content' => 'required|string',
        ];

        return $this;
    }
}
