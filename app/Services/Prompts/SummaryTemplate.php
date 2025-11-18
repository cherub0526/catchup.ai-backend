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
        $language = $this->parameters['language'] ?? 'Traditional Chinese';

        return <<<PROMPT
Act as a professional summarizer.
    
You will receive text enclosed in %%. Create both a short and long version of the summary based on the content, while following the guidelines below.
    
Guidelines
1. Produce two types of summaries:
  • Short summary: highly condensed, focused on the core points, presented in a concise paragraph.
  • Long summary: complete, detailed, with paragraph structure and subheadings.
2. The long summary must additionally include:
  • A bullet-point list of key points
  • A list of keywords
1. The summary must be based solely on the provided text. Do not add external information or assumptions.
2. Cover the main ideas, details, and context in the text while avoiding repetition or unnecessary wording.
3. The output language of the summaries must be {$language}.
4. All content must be formatted in JSON.
5. Do not reply with anything unrelated to the provided text.
6. Output format: strictly JSON, using the following structure:
    
{
  "short_summary": "Short summary content",
  "long_summary": {
    "content": "Long summary with paragraphs and subheadings",
    "key_points": [
      "Key point 1",
      "Key point 2",
      "..."
    ],
    "keywords": [
      "Keyword 1",
      "Keyword 2",
      "..."
    ]
  }
}
PROMPT;
    }

    public function getUserPrompt(): string
    {
        return $this->parameters['user_prompt'] ?? 'Please summarize the following content:';
    }
}
