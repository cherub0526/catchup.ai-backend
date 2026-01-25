<?php

declare(strict_types=1);

namespace App\Validators;

class ImageValidator extends BaseValidator
{
    public function __construct($params)
    {
        parent::__construct($params);
    }

    public function setStoreRules(): self
    {
        $this->rules = [
            'images.*' => 'file|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ];
        return $this;
    }
}
