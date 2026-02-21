<?php

use Illuminate\Support\Str;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

beforeEach(function () {
    if (! shouldRunCloudflareLiveTests()) {
        $this->markTestSkipped('Set CLOUDFLARE_LIVE_TESTS=true with valid Cloudflare credentials to run live adapter tests.');
    }

    Http::allowStrayRequests();
});

it('uploads an image, returns a public URL, and deletes it using the Cloudflare Images adapter', function () {
    /** @var \Illuminate\Filesystem\FilesystemAdapter $disk */
    $disk = Storage::disk('cloudflare-images');

    $id = Str::random(20);
    $file = UploadedFile::fake()->image('image.jpg', 50, 50);

    expect($disk->putFileAs('', $file, $id))->toBeString();
    expect($disk->url($id))->toContain($id);
    expect($disk->delete($id))->toBeTrue();
});

it('reads an uploaded image both as a string and as a stream using the Cloudflare Images adapter', function () {
    /** @var \Illuminate\Filesystem\FilesystemAdapter $disk */
    $disk = Storage::disk('cloudflare-images');

    $id = Str::random(20);
    $file = UploadedFile::fake()->image('image.jpg', 50, 50);

    $disk->put($id, file_get_contents($file->getPathname()));

    expect($disk->get($id))->toBeString();

    $stream = $disk->readStream($id);

    expect($stream)->toBeResource();

    fclose($stream);

    expect($disk->delete($id))->toBeTrue();
});

function shouldRunCloudflareLiveTests() : bool
{
    return 'true' === strtolower((string) (getenv('CLOUDFLARE_LIVE_TESTS') ?: 'false'))
        && filled(config('services.cloudflare_images.account_id'))
        && filled(config('services.cloudflare_images.account_hash'))
        && filled(config('services.cloudflare_images.token'));
}
