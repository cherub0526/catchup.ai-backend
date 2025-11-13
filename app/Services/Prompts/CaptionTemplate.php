<?php

declare(strict_types=1);

namespace App\Services\Prompts;

/**
 * 字幕生成模板
 * 用於視頻字幕生成和字幕相關任務.
 */
class CaptionTemplate extends BaseTemplate
{
    protected string $type = 'caption';

    public function getSystemPrompt(): string
    {
        $language = $this->parameters['language'] ?? 'Traditional Chinese';
        $style = $this->parameters['style'] ?? 'neutral';

        return <<<PROMPT
You are a professional caption writer. Your task is to create accurate and engaging captions.
- Language: {$language}
- Style: {$style}
- Keep captions concise and clear
- Synchronize with video content
- Use proper punctuation and grammar
- Maintain speaker's tone and emphasis
PROMPT;
    }

    public function getUserPrompt(): string
    {
        return $this->parameters['user_prompt'] ?? 'Please generate captions for the following video content:';
    }
}
