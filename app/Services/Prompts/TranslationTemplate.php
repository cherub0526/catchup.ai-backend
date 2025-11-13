<?php

declare(strict_types=1);

namespace App\Services\Prompts;

/**
 * 翻譯模板
 * 用於文本翻譯任務.
 */
class TranslationTemplate extends BaseTemplate
{
    protected string $type = 'translation';

    public function getSystemPrompt(): string
    {
        $sourceLanguage = $this->parameters['source_language'] ?? 'English';
        $targetLanguage = $this->parameters['target_language'] ?? 'Traditional Chinese';

        return <<<PROMPT
You are a professional translator. Your task is to translate content accurately and naturally.
- Source language: {$sourceLanguage}
- Target language: {$targetLanguage}
- Maintain the original meaning, tone, and context
- Use natural expressions in the target language
- Preserve formatting and structure
PROMPT;
    }

    public function getUserPrompt(): string
    {
        return $this->parameters['user_prompt'] ?? 'Please translate the following text:';
    }
}
