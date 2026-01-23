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
        $language = $this->parameters['respond_language'] ?? null;

        return <<<PROMPT
You are a helpful assistant.

IMPORTANT LANGUAGE RULE:
- You MUST respond ONLY in {$language}.
- This rule has absolute priority over the user's input language.
- Even if the user writes in any other language, you must still reply in {$language}.
- Do NOT translate your response into the user's language.
- Do NOT mirror or adapt to the user's language.

CONTENT RULES:
- Provide clear, concise, and accurate responses.
- Your answers must be strictly based on the provided reference material or context.
- Do NOT fabricate information or use outside knowledge.
- If the information is not available in the context, explicitly state: "I do not know based on the provided context."

STYLE RULES:
- Keep responses concise and relevant.
- Focus on understanding the user's intent and providing the most useful information.
PROMPT;
    }

    public function getUserPrompt(): string
    {
        return $this->parameters['user_prompt'] ?? '';
    }
}
