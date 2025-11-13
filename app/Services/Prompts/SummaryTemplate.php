<?php

declare(strict_types=1);

namespace App\Services\Prompts;

/**
 * 摘要模板
 * 用於文本摘要和總結任務.
 */
class SummaryTemplate extends BaseTemplate
{
    protected string $type = 'summary';

    public function getSystemPrompt(): string
    {
        $length = $this->parameters['length'] ?? 'medium';
        $language = $this->parameters['language'] ?? 'Traditional Chinese';

        return <<<PROMPT
You are a professional summarizer. Your task is to create clear and concise summaries.
- Language: {$language}
- Summary length: {$length} (short: 1-2 paragraphs, medium: 3-5 paragraphs, long: 6+ paragraphs)
- Focus on key points and main ideas
- Preserve important details and context
PROMPT;
    }

    public function getUserPrompt(): string
    {
        return $this->parameters['user_prompt'] ?? 'Please summarize the following content:';
    }
}
