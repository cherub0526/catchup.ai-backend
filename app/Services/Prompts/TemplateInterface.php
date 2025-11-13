<?php

declare(strict_types=1);

namespace App\Services\Prompts;

/**
 * 模板介面
 * 定義所有 Template 必須實現的方法.
 */
interface TemplateInterface
{
    /**
     * 取得模板的系統提示詞.
     */
    public function getSystemPrompt(): string;

    /**
     * 取得模板的使用者提示詞.
     */
    public function getUserPrompt(): string;

    /**
     * 取得模板類型.
     */
    public function getType(): string;

    /**
     * 取得模板的參數.
     */
    public function getParameters(): array;

    /**
     * 構建用於 OpenAI API 的消息陣列.
     */
    public function buildMessages(string $userContent, array $additionalParams = []): array;
}
