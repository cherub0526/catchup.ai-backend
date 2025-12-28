<?php

declare(strict_types=1);

namespace App\Services;

use DateTimeInterface;
use Hypervel\Support\Facades\Storage;

class StorageService
{
    /**
     * Upload a file to S3.
     *
     * @param string $source the absolute path to the source file
     * @param string $destination the destination path on S3
     */
    public function upload(string $source, string $destination): bool
    {
        if (!file_exists($source)) {
            return false;
        }

        $stream = fopen($source, 'r');

        try {
            return Storage::disk('s3')->put($destination, $stream);
        } finally {
            if (is_resource($stream)) {
                fclose($stream);
            }
        }
    }

    /**
     * Generate a temporary shared link for the file.
     *
     * @param string $path the path to the file on S3
     * @param DateTimeInterface $expiration the expiration time
     */
    public function getTemporaryUrl(string $path, DateTimeInterface $expiration): string
    {
        return Storage::disk('s3')->temporaryUrl($path, $expiration);
    }
}
