<?php

namespace App\Contracts;

/**
 * Defines how fallback post-image generation asks for a browser screenshot.
 *
 * An implementation must render the given URL to the exact output path or throw
 * an error. The upload starts only after that file exists. This interface keeps
 * the command independent from one browser tool and lets tests use a fake capture.
 */
interface PostImageScreenshotter
{
    public function capture(string $url, string $outputPath) : void;
}
