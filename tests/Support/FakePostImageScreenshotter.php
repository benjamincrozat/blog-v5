<?php

namespace Tests\Support;

use Illuminate\Support\Facades\File;
use App\Contracts\PostImageScreenshotter;

/**
 * Captures screenshot requests without launching a browser.
 */
class FakePostImageScreenshotter implements PostImageScreenshotter
{
    /**
     * @var array<int, array{url: string, outputPath: string}>
     */
    public array $captures = [];

    public function capture(string $url, string $outputPath) : void
    {
        $this->captures[] = [
            'url' => $url,
            'outputPath' => $outputPath,
        ];

        File::ensureDirectoryExists(dirname($outputPath));
        File::put($outputPath, base64_decode(
            'iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mP8/x8AAusB9Wn0l1sAAAAASUVORK5CYII=',
            true,
        ) ?: '');
    }
}
