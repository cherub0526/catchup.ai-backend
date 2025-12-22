<?php

declare(strict_types=1);

namespace App\Utils\OpenAI;

use RuntimeException;
use Hypervel\Support\Facades\Http;

class Completion
{
    private string $apiKey;

    private string $baseUri;

    public function __construct(
        string $apiKey,
        string $baseUri = 'https://api.openai.com/v1'
    ) {
        $this->apiKey = $apiKey;
        $this->baseUri = rtrim($baseUri, '/');
    }

    /**
     * Call OpenAI completions.
     *
     * @param array $options Additional OpenAI parameters (e.g. max_tokens, temperature)
     * @return array Decoded JSON response
     * @throws RuntimeException on HTTP / JSON errors
     */
    public function completions(string $model, array $messages, array $options = []): array
    {
        $payload = array_merge([
            'model'    => $model,
            'messages' => $messages,
            // sensible default; can be overridden via $options
            'max_tokens'  => 20000,
            'temperature' => 0.7,
            'top_p'       => 1,
        ], $options);

        return $this->send('/chat/completions', $payload);
    }

    private function send(string $path, array $payload): array
    {
        $url = $this->baseUri . $path;

        return Http::withToken($this->apiKey)->timeout(60)->acceptJson()->post($url, $payload)->json();
    }
}
