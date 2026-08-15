<?php

namespace App\Support;

use RuntimeException;
use Spatie\Browsershot\Browsershot;
use App\Contracts\PostImageScreenshotter;

/**
 * Takes post-image screenshots with the local Browsershot and Node setup.
 *
 * It opens the preview at 1280x720, waits for the page to finish loading, keeps
 * the background, and writes the file within 60 seconds. If no file is created,
 * it throws so the upload cannot continue with a missing image.
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
