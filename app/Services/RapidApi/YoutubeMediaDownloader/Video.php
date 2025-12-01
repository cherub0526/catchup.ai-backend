<?php

declare(strict_types=1);

namespace App\Services\RapidApi\YoutubeMediaDownloader;

use App\Services\RapidApi\YoutubeMediaDownloader;

class Video
{
    protected YoutubeMediaDownloader $downloader;

    protected string $endpoint = '/v2/video';

    /**
     * YouTube video id. The value of v in YouTube player URL query parameters.
     */
    protected string $videoId;

    /**
     * Accessibility to video/audio URLs. Defaults to normal.
     *
     * normal: Includes video/audio file URLs – 3 quota units
     * blocked: Excludes video/audio file URLs – 1 quota unit
     */
    protected string $urlAccess = 'normal';

    /**
     * Language code (IETF language tag) for localized results. Defaults to en-US.
     * Unsupported code will fall back to en-US.
     */
    protected string $lang = 'en-US';

    /**
     * Whether to get video objects. Defaults to auto.
     *
     * true: Includes simplified objects.
     * raw: Includes original format objects.
     * false: Excludes objects.
     * auto: If urlAccess=normal, sets videos=true. If urlAccess=blocked, sets videos=false.
     */
    protected string $videos = 'auto';

    /**
     * Whether to get audio objects. Defaults to auto.
     *
     * true: Includes simplified objects.
     * raw: Includes original format objects.
     * false: Excludes objects.
     * auto: If urlAccess=normal, sets audios=true. If urlAccess=blocked, sets audios=false.
     */
    protected string $audios = 'auto';

    /**
     * Whether to get subtitle URLs. Defaults to true.
     *
     *  true:
     *  false:
     *  rapid_do_not_include_in_request_key:
     */
    protected string $subtitles = 'true';

    /**
     * Whether to get information of related videos and playlists. Defaults to true.
     *
     * true:
     * false:
     * rapid_do_not_include_in_request_key:
     */
    protected string $related = 'true';

    public function __construct(YoutubeMediaDownloader $downloader)
    {
        $this->downloader = $downloader;
    }

    public function setVideoId(string $videoId): self
    {
        $this->videoId = $videoId;

        return $this;
    }

    public function setUrlAccess(string $urlAccess): self
    {
        $this->urlAccess = $urlAccess;

        return $this;
    }

    public function setLang(string $lang): self
    {
        $this->lang = $lang;

        return $this;
    }

    public function setVideos(string $videos): self
    {
        $this->videos = $videos;

        return $this;
    }

    public function setAudios(string $audios): self
    {
        $this->audios = $audios;

        return $this;
    }

    public function setSubtitles(string $subtitles): self
    {
        $this->subtitles = $subtitles;

        return $this;
    }

    public function setRelated(string $related): self
    {
        $this->related = $related;

        return $this;
    }

    public function details()
    {
        return $this->downloader->send('get', $this->endpoint . '/details', [
            'videoId' => $this->videoId,
            'urlAccess' => $this->urlAccess,
            'lang' => $this->lang,
            'videos' => $this->videos,
            'audios' => $this->audios,
            'subtitles' => $this->subtitles,
            'related' => $this->related,
        ]);
    }
}
