<?php

namespace App\Contracts;

/**
 * Captures screenshot assets for generated post images.
 */
interface PostImageScreenshotter
{
    public function capture(string $url, string $outputPath) : void;
}
