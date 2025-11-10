<?php

declare(strict_types=1);

namespace App\Validators;

class MediaValidator extends BaseValidator
{
    public function __construct($params)
    {
        parent::__construct($params);
    }

    public function setIndexRules(): self
    {
        $this->rules = [
            'type' => 'required|string|in:' . implode(',', array_keys(\App\Models\Media::$typeMaps)),
            'limit' => 'sometimes|integer|min:1|max:10',
        ];

        return $this;
    }
}
