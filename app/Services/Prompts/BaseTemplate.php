<?php

declare(strict_types=1);

namespace App\Services\Prompts;

/**
 * 基礎模板類別
 * 提供通用的模板功能.
 */
abstract class BaseTemplate implements TemplateInterface
{
    /**
     * @var array 模板參數
     */
    protected array $parameters = [];

    /**
     * @var string 模板類型
     */
    protected string $type = 'base';

    /**
     * 建構函式.
     */
    public function __construct(array $parameters = [])
    {
        $this->parameters = $parameters;
    }

    /**
     * 取得模板類型.
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * 取得模板的參數.
     */
    public function getParameters(): array
    {
        return $this->parameters;
    }

    /**
     * 設定參數.
     */
    public function setParameters(array $parameters): self
    {
        $this->parameters = array_merge($this->parameters, $parameters);
        return $this;
    }

    /**
     * 構建用於 OpenAI API 的消息陣列.
     */
    public function buildMessages(string $userContent, array $additionalParams = []): array
    {
        $messages = [];

        // 新增系統提示詞
        $systemPrompt = $this->getSystemPrompt();
        if (! empty($systemPrompt)) {
            $messages[] = [
                'role' => 'system',
                'content' => $systemPrompt,
            ];
        }

        if (isset($additionalParams['messages']) && is_array($additionalParams['messages'])) {
            foreach ($additionalParams['messages'] as $history) {
                if (isset($history['role'], $history['content'])) {
                    $messages[] = [
                        'role' => $history['role'],
                        'content' => $history['content'],
                    ];
                }
            }
        }

        // 新增使用者提示詞
        $userPrompt = $this->getUserPrompt();
        if (! empty($userPrompt)) {
            $messages[] = [
                'role' => 'user',
                'content' => $userPrompt . "\n\n" . $userContent,
            ];
        } else {
            $messages[] = [
                'role' => 'user',
                'content' => $userContent,
            ];
        }

        return $messages;
    }

    /**
     * 取得模板的系統提示詞.
     */
    abstract public function getSystemPrompt(): string;

    /**
     * 取得模板的使用者提示詞.
     */
    abstract public function getUserPrompt(): string;
}
