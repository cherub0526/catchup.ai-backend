<?php

declare(strict_types=1);

namespace App\Validators;

class YoutubeMp3DownloaderValidator extends BaseValidator
{
    public function __construct($params)
    {
        parent::__construct($params);

        $this->messages = [
            'status.required'      => __('validators.youtube_mp3_downloader.status.required'),
            'status.in'            => __('validators.youtube_mp3_downloader.status.in'),
            'data.status.required' => __('validators.youtube_mp3_downloader.data.status.required'),
            'data.status.in'       => __('validators.youtube_mp3_downloader.data.status.in'),
            'data.link.required'   => __('validators.youtube_mp3_downloader.data.link.required'),
            'data.link.active_url' => __('validators.youtube_mp3_downloader.data.link.active_url'),
        ];
    }

    public function setStoreRules(): self
    {
        $this->rules = [
            'status'      => ['required', 'in:' . implode(',', ['success', 'error'])],
            'data.status' => ['required', 'in:' . implode(',', ['ok', 'processing', 'fail'])],
            'data.link'   => ['required', 'active_url'],
        ];

        return $this;
    }
}
