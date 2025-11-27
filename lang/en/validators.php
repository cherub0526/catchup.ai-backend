<?php

declare(strict_types=1);

return [
    'controllers' => [
        'auth' => [
            'invalid_credentials' => 'Invalid account or password.',
        ],
        'media' => [
            'not_found' => 'Media not found.',
        ],
        'rss' => [
            'invalid_url' => 'Invalid RSS URL.',
            'not_found' => 'RSS not found.',
        ],
        'subscription' => [
            'plan_not_found' => 'Plan not found.',
            'price_not_found' => 'Price not found.',
            'price_not_in_plan' => 'Price not found in plan.',
            'not_found' => 'Subscription not found.',
        ],
    ],
    'auth' => [
        'account' => [
            'required' => 'Account is required.',
            'string' => 'Account must be a string.',
            'min' => 'Account must be at least 6 characters.',
            'max' => 'Account must not exceed 255 characters.',
            'unique' => 'Account already exists.',
        ],
        'email' => [
            'required' => 'Email is required.',
            'email' => 'Email format is invalid.',
            'max' => 'Email must not exceed 255 characters.',
        ],
        'password' => [
            'required' => 'Password is required.',
            'string' => 'Password must be a string.',
            'min' => 'Password must be at least 8 characters.',
            'confirmed' => 'Password confirmation does not match.',
        ],
        'password_confirmation' => [
            'required' => 'Password confirmation is required.',
            'string' => 'Password confirmation must be a string.',
        ],
    ],
    'chat' => [
        'messages' => [
            'required' => 'The messages field is required.',
            'array' => 'The messages must be an array.',
            'min' => 'The messages must have at least 1 item.',
            'role' => [
                'required' => 'The role field is required.',
                'string' => 'The role must be a string.',
                'in' => 'The selected role is invalid.',
            ],
            'content' => [
                'required' => 'The content field is required.',
                'string' => 'The content must be a string.',
            ],
        ],
    ],
    'media' => [
        'type' => [
            'required' => 'Type is required.',
            'string' => 'Type must be a string.',
            'in' => 'Type is invalid.',
        ],
        'limit' => [
            'integer' => 'Limit must be an integer.',
            'min' => 'Limit must be at least 1.',
            'max' => 'Limit must not exceed 10.',
        ],
    ],
    'oauth' => [
        'provider' => [
            'required' => 'Provider is required.',
            'in' => 'Provider is invalid.',
        ],
        'code' => [
            'required' => 'Code is required.',
        ],
    ],
    'rss' => [
        'type' => [
            'required' => 'Type is required.',
            'string' => 'Type must be a string.',
            'in' => 'Type is invalid.',
        ],
        'url' => [
            'required' => 'URL is required.',
        ],
    ],
    'subscription' => [
        'planId' => [
            'required' => 'Plan ID is required.',
            'string' => 'Plan ID must be a string.',
        ],
        'priceId' => [
            'required' => 'Price ID is required.',
            'string' => 'Price ID must be a string.',
        ],
    ],
    'user' => [
        'name' => [
            'required' => 'Name is required.',
            'string' => 'Name must be a string.',
        ],
        'email' => [
            'required' => 'Email is required.',
            'email' => 'Email format is invalid.',
        ],
    ],
];
