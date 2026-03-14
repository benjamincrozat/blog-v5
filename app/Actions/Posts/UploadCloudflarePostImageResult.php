<?php

namespace App\Actions\Posts;

/**
 * Represents an uploaded Cloudflare post image.
 */
class UploadCloudflarePostImageResult
{
    public function __construct(
        public readonly string $disk,
        public readonly string $path,
        public readonly string $url,
        public readonly ?string $markdownPath,
    ) {}
}
