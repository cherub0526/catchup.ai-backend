<?php

declare(strict_types=1);

return [
    'controllers' => [
        'auth' => [
            'invalid_credentials' => '帳號或密碼無效。',
        ],
        'media' => [
            'not_found'         => '找不到指定的媒體。',
            'caption_not_found' => '找不到指定的字幕。',
        ],
        'rss' => [
            'invalid_url' => '無效的 RSS 網址。',
            'not_found'   => '找不到指定的 RSS。',
        ],
        'subscription' => [
            'plan_not_found'    => '找不到指定的方案。',
            'price_not_found'   => '找不到指定的價格。',
            'price_not_in_plan' => '方案中找不到指定的價格。',
            'not_found'         => '找不到指定的訂閱。',
        ],
        'webhook' => [
            'paddle' => [
                'transaction_not_completed' => '交易狀態未完成。',
            ],
        ],
    ],
    'auth' => [
        'account' => [
            'required' => '帳號為必填。',
            'string'   => '帳號必須是字串。',
            'min'      => '帳號長度至少需要 6 個字元。',
            'max'      => '帳號長度不能超過 255 個字元。',
            'unique'   => '帳號已存在。',
        ],
        'email' => [
            'required' => '電子郵件為必填。',
            'email'    => '電子郵件格式無效。',
            'max'      => '電子郵件長度不能超過 255 個字元。',
        ],
        'password' => [
            'required'  => '密碼為必填。',
            'string'    => '密碼必須是字串。',
            'min'       => '密碼長度至少需要 8 個字元。',
            'confirmed' => '確認密碼不相符。',
        ],
        'password_confirmation' => [
            'required' => '確認密碼為必填。',
            'string'   => '確認密碼必須是字串。',
        ],
    ],
    'chat' => [
        'messages' => [
            'required' => '訊息為必填。',
            'array'    => '訊息必須是陣列。',
            'min'      => '訊息至少需要 1 個項目。',
            'role'     => [
                'required' => '角色為必填。',
                'string'   => '角色必須是字串。',
                'in'       => '選擇的角色無效。',
            ],
            'content' => [
                'required' => '內容為必填。',
                'string'   => '內容必須是字串。',
            ],
        ],
    ],
    'media' => [
        'type' => [
            'required' => '類型為必填。',
            'string'   => '類型必須是字串。',
            'in'       => '類型無效。',
        ],
        'limit' => [
            'integer' => '限制必須是整數。',
            'min'     => '限制至少為 1。',
            'max'     => '限制不能超過 10。',
        ],
    ],
    'oauth' => [
        'provider' => [
            'required' => '提供者為必填。',
            'in'       => '提供者無效。',
        ],
        'code' => [
            'required' => '代碼為必填。',
        ],
    ],
    'rss' => [
        'type' => [
            'required' => '類型為必填。',
            'string'   => '類型必須是字串。',
            'in'       => '類型無效。',
        ],
        'url' => [
            'required' => 'URL 為必填。',
        ],
    ],
    'subscription' => [
        'planId' => [
            'required' => '方案 ID 為必填。',
            'string'   => '方案 ID 必須是字串。',
        ],
        'priceId' => [
            'required' => '價格 ID 為必填。',
            'string'   => '價格 ID 必須是字串。',
        ],
    ],
    'user' => [
        'name' => [
            'required' => '名稱為必填。',
            'string'   => '名稱必須是字串。',
        ],
        'email' => [
            'required' => '電子郵件為必填。',
            'email'    => '電子郵件格式無效。',
        ],
    ],

    'groq' => [
        'status' => [
            'required' => '狀態欄位為必填。',
        ],
        'data' => [
            'required' => '資料欄位為必填。',
            'language' => [
                'required' => '語言欄位為必填。',
            ],
            'duration' => [
                'required' => '時長欄位為必填。',
            ],
            'text' => [
                'required' => '文本欄位為必填。',
            ],
            'words' => [
                'required' => '字詞欄位為必填。',
            ],
            'segments' => [
                'required' => '片段欄位為必填。',
            ],
            'error' => [
                'required' => '錯誤訊息為必填。',
            ],
        ],
    ],
];
