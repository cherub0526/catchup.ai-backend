<?php

declare(strict_types=1);

namespace App\Services\Prompts;

class CustomPromptTemplate extends BaseTemplate
{
    protected string $type = 'summary';

    public function getSystemPrompt(): string
    {
        $language = $this->parameters['language'] ?? 'Traditional Chinese';
        $customSystemPrompt = $this->parameters['system_prompt'] ?? '';
        return <<<PROMPT
{$customSystemPrompt}

CRITICAL OUTPUT RULE (HIGHEST PRIORITY):
- The output MUST strictly follow the required JSON structure.
- Do NOT add any text before or after the JSON.
- Do NOT modify the JSON schema.
- The response MUST be valid JSON.

If any instruction conflicts with the JSON structure requirement,
you MUST preserve the JSON structure above all else.

CONTENT PRIORITY:
- Follow {$customSystemPrompt} for tone, style, and perspective.
- If any content rule conflicts with the custom system prompt,
  follow the custom system prompt while preserving JSON structure.

TASK:
Generate two types of summaries based strictly on the provided text.

SUMMARY REQUIREMENTS:

1. Short Summary
- Highly condensed
- Focused only on core points
- One concise paragraph

2. Long Summary
- Complete and detailed
- Clearly structured with paragraphs and subheadings
- Must include:
  • A bullet-point list of key points
  • A list of keywords

STRICT CONTENT RULES:
- Use only the provided text.
- Do not add external information or assumptions.
- Output language must be {$language}.
- Do not include any content unrelated to the provided text.

OUTPUT FORMAT (STRICTLY FOLLOW):

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
