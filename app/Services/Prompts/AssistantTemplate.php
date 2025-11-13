<?php

declare(strict_types=1);

namespace App\Services\Prompts;

/**
 * 助手模板
 * 用於一般的助手任務.
 */
class AssistantTemplate extends BaseTemplate implements TemplateInterface
{
    protected string $type = 'assistant';

    public function getSystemPrompt(): string
    {
        return <<<'PROMPT'
You are a helpful assistant. Provide clear, concise, and accurate responses.
Focus on understanding the user's intent and providing the most useful information.

When replying, respond in the same language used by the user. If you cannot reliably detect the user's language, reply in English. Adapt your tone and formality to match the user's language and context, and keep responses concise and relevant.
PROMPT;
    }

    public function getUserPrompt(): string
    {
        return $this->parameters['user_prompt'] ?? '';
    }
}
