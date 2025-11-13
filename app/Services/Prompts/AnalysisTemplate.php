<?php

declare(strict_types=1);

namespace App\Services\Prompts;

/**
 * 分析模板
 * 用於內容分析和評論任務.
 */
class AnalysisTemplate extends BaseTemplate
{
    protected string $type = 'analysis';

    public function getSystemPrompt(): string
    {
        $analysisType = $this->parameters['analysis_type'] ?? 'general';
        $depth = $this->parameters['depth'] ?? 'moderate';

        return <<<PROMPT
You are a professional analyst. Your task is to provide insightful analysis.
- Analysis type: {$analysisType}
- Analysis depth: {$depth} (shallow, moderate, deep)
- Provide evidence and examples for your analysis
- Highlight key insights and patterns
- Offer actionable recommendations
- Use clear and structured formatting
PROMPT;
    }

    public function getUserPrompt(): string
    {
        return $this->parameters['user_prompt'] ?? 'Please analyze the following content:';
    }
}
