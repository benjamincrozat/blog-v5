<?php

namespace App\Support;

use RuntimeException;
use Spatie\Browsershot\Browsershot;
use App\Contracts\PostImageScreenshotter;

/**
 * Captures post image screenshots with Browsershot.
 */
class BrowsershotPostImageScreenshotter implements PostImageScreenshotter
{
    public function capture(string $url, string $outputPath) : void
    {
        Browsershot::url($url)
            ->setNodeBinary((string) config('blog.screenshot.node_binary'))
            ->setNpmBinary((string) config('blog.screenshot.npm_binary'))
            ->setNodeModulePath(base_path('node_modules'))
            ->windowSize(1280, 720)
            ->showBackground()
            ->waitUntilNetworkIdle()
            ->timeout(60)
            ->save($outputPath);

        if (! file_exists($outputPath)) {
            throw new RuntimeException("Post image screenshot [{$outputPath}] was not created.");
        }
    }
}
