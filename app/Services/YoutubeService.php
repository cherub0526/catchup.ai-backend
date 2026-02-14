<?php

declare(strict_types=1);

namespace App\Services;

use Exception;
use Google\Client;
use Google\Service\YouTube;

class YoutubeService
{
    protected YouTube $youtube;

    public function __construct()
    {
        $client = new Client();
        $client->setDeveloperKey(env('YOUTUBE_API_KEY'));
        $this->youtube = new YouTube($client);
    }

    /**
     * Get channel ID from a YouTube channel URL.
     *
     * Supports:
     * - https://www.youtube.com/channel/UC...
     * - https://www.youtube.com/c/CustomName
     * - https://www.youtube.com/user/Username
     * - https://www.youtube.com/@Handle
     *
     * @return null|string Channel ID or null if not found
     */
    public function getChannelIdFromUrl(string $url): ?string
    {
        $parsedUrl = parse_url($url);
        if (!isset($parsedUrl['path'])) {
            return null;
        }

        $path = trim($parsedUrl['path'], '/');
        $segments = explode('/', $path);

        if (empty($segments)) {
            return null;
        }

        // Case 1: /channel/ID
        if ($segments[0] === 'channel' && isset($segments[1])) {
            return $segments[1];
        }

        // Case 2: /@Handle
        if (str_starts_with($segments[0], '@')) {
            return $this->getChannelIdByHandle($segments[0]);
        }

        // Case 3: /c/CustomName or /user/Username
        if (($segments[0] === 'c' || $segments[0] === 'user') && isset($segments[1])) {
            // For both custom URLs and legacy usernames, we can search
            // Note: 'user' might need channels->list with forUsername, but search is often more robust for mixed inputs
            // Let's try to resolve via search for custom URL or username
            return $this->searchChannelId($segments[1]);
        }

        // Case 4: Just a custom name at root (e.g. youtube.com/google) - treated as handle or custom URL
        // This is ambiguous, but we can try searching for it as a handle or query
        return $this->getChannelIdByHandle('@' . $segments[0]) ?? $this->searchChannelId($segments[0]);
    }

    protected function getChannelIdByHandle(string $handle): ?string
    {
        try {
            $response = $this->youtube->channels->listChannels('id', ['forHandle' => $handle]);
            $items = $response->getItems();
            if (!empty($items)) {
                return $items[0]->getId();
            }
        } catch (Exception $e) {
            // Log error or handle gracefully
        }

        return null;
    }

    protected function searchChannelId(string $query): ?string
    {
        try {
            $response = $this->youtube->search->listSearch('snippet', [
                'q'          => $query,
                'type'       => 'channel',
                'maxResults' => 1,
            ]);

            $items = $response->getItems();
            if (!empty($items)) {
                return $items[0]->getSnippet()->getChannelId();
            }
        } catch (Exception $e) {
            // Log error or handle gracefully
        }

        return null;
    }
}
