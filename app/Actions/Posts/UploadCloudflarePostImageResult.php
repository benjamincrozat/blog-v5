<?php

namespace App\Actions\Posts;

/**
 * Holds the storage details for one uploaded post image.
 *
 * The disk and path can be saved in Markdown front matter, while the public URL
 * can be pasted into an article. All three read-only values point to the same
 * Cloudflare image and delivery style.
 */
class UploadCloudflarePostImageResult
{
    public function __construct(
        public readonly string $disk,
        public readonly string $path,
        public readonly string $url,
    ) {}
}
