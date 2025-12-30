<?php

declare(strict_types=1);

namespace App\Validators;

class GroqValidator extends BaseValidator
{
    public const string STATUS_SUCCESS = 'success';
    public const string STATUS_ERROR = 'error';

    public static array $statusMaps = [
        self::STATUS_SUCCESS => '成功',
        self::STATUS_ERROR   => '失敗',
    ];

    public function __construct($params)
    {
        parent::__construct($params);

        $this->messages = [
            'status.required'        => __('validators.groq.status.required'),
            'data.required'          => __('validators.groq.data.required'),
            'data.language.required' => __('validators.groq.data.language.required'),
            'data.duration.required' => __('validators.groq.data.duration.required'),
            'data.text.required'     => __('validators.groq.data.text.required'),
            'data.words.required'    => __('validators.groq.data.words.required'),
            'data.segments.required' => __('validators.groq.data.segments.required'),
            'data.error.required'    => __('validators.groq.data.error.required'),
        ];
    }

    public function setStoreRules(): self
    {
        $this->rules = [
            'status' => 'required',
            'data'   => 'required',
        ];

        $this->params['status'] = $this->params['status'] ?? null;

        if ($this->params['status'] === self::STATUS_SUCCESS) {
            $this->rules = array_merge(
                $this->rules,
                [
                    'data.language' => 'required',
                    'data.duration' => 'required',
                    'data.text'     => 'required',
                    'data.words'    => 'required',
                    'data.segments' => 'required',
                ],
            );
        }

        if ($this->params['status'] === self::STATUS_ERROR) {
            $this->rules = array_merge(
                $this->rules,
                [
                    'data.error' => 'required',
                ]
            );
        }

        return $this;
    }
}
