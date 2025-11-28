<?php

use Illuminate\Support\Str;
use League\Flysystem\Config;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use League\Flysystem\UnableToWriteFile;
use League\Flysystem\UnableToDeleteFile;
use App\Filesystem\CloudflareImagesAdapter;
use League\Flysystem\UnableToCreateDirectory;
use League\Flysystem\UnableToDeleteDirectory;
use League\Flysystem\UnableToRetrieveMetadata;
use League\Flysystem\InvalidVisibilityProvided;

it('checks file existence using Cloudflare API', function () {
    $adapter = new CloudflareImagesAdapter('test-token', 'acct_123', 'hash_abc', 'public');

    Http::fakeSequence('https://api.cloudflare.com/client/v4/accounts/acct_123/images/v1/*')
        ->push('', 200)
        ->push('', 404);

    expect($adapter->fileExists('image.jpg'))->toBeTrue();
    expect($adapter->fileExists('image.jpg'))->toBeFalse();
});

it('uploads content via write and writeStream', function () {
    $adapter = new CloudflareImagesAdapter('test-token', 'acct_123', 'hash_abc', 'public');

    Http::fake([
        'https://api.cloudflare.com/*' => Http::response([], 200),
    ]);

    $adapter->write('folder/image.jpg', 'file-contents', new Config);

    Http::assertSent(function (Illuminate\Http\Client\Request $request) {
        return 'POST' === $request->method()
            && str_contains($request->url(), '/images/v1');
    });

    // Stream upload
    $stream = fopen('php://temp', 'r+');
    fwrite($stream, 'file-contents');
    rewind($stream);

    $adapter->writeStream('folder/image2.jpg', $stream, new Config);
});

it('throws when upload fails', function () {
    $adapter = new CloudflareImagesAdapter('test-token', 'acct_123', 'hash_abc', 'public');

    Http::fake([
        'https://api.cloudflare.com/*' => Http::response('nope', 500),
    ]);

    expect(fn () => $adapter->write('image.jpg', 'x', new Config))
        ->toThrow(UnableToWriteFile::class);
});

it('reads content and streams from public URL', function () {
    $adapter = new CloudflareImagesAdapter('test-token', 'acct_123', 'hash_abc', 'public');

    Http::fake([
        'https://imagedelivery.net/*' => Http::response('IMG', 200),
    ]);

    expect($adapter->read('folder/image.jpg'))->toBe('IMG');

    $stream = $adapter->readStream('folder/image.jpg');
    expect(is_resource($stream))->toBeTrue();
});

it('deletes images and throws on failure', function () {
    $adapter = new CloudflareImagesAdapter('test-token', 'acct_123', 'hash_abc', 'public');

    Http::fakeSequence('https://api.cloudflare.com/client/v4/accounts/acct_123/images/v1/*')
        ->push('', 200)
        ->push('', 500);

    // First delete succeeds, second fails and should throw.
    $adapter->delete('folder/image.jpg');

    expect(fn () => $adapter->delete('folder/image.jpg'))
        ->toThrow(UnableToDeleteFile::class);
});

it('retrieves metadata from headers', function () {
    $adapter = new CloudflareImagesAdapter('test-token', 'acct_123', 'hash_abc', 'public');

    Http::fake([
        'https://imagedelivery.net/*' => Http::response('', 200, [
            'Content-Type' => 'image/webp',
            'Last-Modified' => 'Wed, 21 Oct 2015 07:28:00 GMT',
            'Content-Length' => '1234',
        ]),
    ]);

    $mime = $adapter->mimeType('folder/image.jpg');
    $lastModified = $adapter->lastModified('folder/image.jpg');
    $size = $adapter->fileSize('folder/image.jpg');

    expect($mime->mimeType())->toBe('image/webp');
    expect($lastModified->lastModified())->toBe(strtotime('Wed, 21 Oct 2015 07:28:00 GMT'));
    expect($size->fileSize())->toBe(1234);
});

it('throws when file size header is missing', function () {
    $adapter = new CloudflareImagesAdapter('test-token', 'acct_123', 'hash_abc', 'public');

    Http::fake([
        'https://imagedelivery.net/*' => Http::response('', 200, [
            'Content-Type' => 'image/webp',
        ]),
    ]);

    expect(fn () => $adapter->fileSize('folder/image.jpg'))
        ->toThrow(UnableToRetrieveMetadata::class);
});

it('reports directories as unsupported', function () {
    $adapter = new CloudflareImagesAdapter('test-token', 'acct_123', 'hash_abc', 'public');

    expect($adapter->directoryExists('any'))->toBeFalse();
    expect(fn () => $adapter->createDirectory('any', new Config))
        ->toThrow(UnableToCreateDirectory::class);
    expect(fn () => $adapter->deleteDirectory('any'))
        ->toThrow(UnableToDeleteDirectory::class);
});

it('enforces public visibility', function () {
    $adapter = new CloudflareImagesAdapter('test-token', 'acct_123', 'hash_abc', 'public');

    expect(fn () => $adapter->setVisibility('any', 'private'))
        ->toThrow(InvalidVisibilityProvided::class);
    expect($adapter->visibility('any')->visibility())->toBe('public');
});

it('returns empty listing results', function () {
    $adapter = new CloudflareImagesAdapter('test-token', 'acct_123', 'hash_abc', 'public');

    expect(iterator_to_array($adapter->listContents('any', false)))->toBe([]);
});

it('formats Cloudflare URLs with the configured variant', function () {
    $adapter = new CloudflareImagesAdapter('test-token', 'acct_123', 'hash_abc', 'public');

    expect($adapter->getUrl('folder/image.jpg'))
        ->toBe('https://imagedelivery.net/hash_abc/folder/image.jpg/public');
});

beforeEach(fn () => Http::allowStrayRequests());

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

it('does not allow moving files', function () {
    /** @var \Illuminate\Filesystem\FilesystemAdapter $disk */
    $disk = Storage::disk('cloudflare-images');

    /** @var \App\Filesystem\CloudflareImagesAdapter $adapter */
    $adapter = $disk->getAdapter();

    expect(fn () => $adapter->move('source', 'destination', new \League\Flysystem\Config))
        ->toThrow(\League\Flysystem\UnableToMoveFile::class);
});

it('does not allow copying files', function () {
    /** @var \Illuminate\Filesystem\FilesystemAdapter $disk */
    $disk = Storage::disk('cloudflare-images');

    /** @var \App\Filesystem\CloudflareImagesAdapter $adapter */
    $adapter = $disk->getAdapter();

    expect(fn () => $adapter->copy('source', 'destination', new \League\Flysystem\Config))
        ->toThrow(\League\Flysystem\UnableToCopyFile::class);
});

it('throws when reading content fails', function () {
    $adapter = new CloudflareImagesAdapter('token', 'acct', 'hash', 'public');

    Http::fake([
        'https://imagedelivery.net/*' => Http::response('', 404),
    ]);

    expect(fn () => $adapter->read('missing.jpg'))
        ->toThrow(\League\Flysystem\UnableToReadFile::class);
});

it('throws when streaming content fails', function () {
    $adapter = new CloudflareImagesAdapter('token', 'acct', 'hash', 'public');

    Http::fake([
        'https://imagedelivery.net/*' => Http::response('', 500),
    ]);

    expect(fn () => $adapter->readStream('missing.jpg'))
        ->toThrow(\League\Flysystem\UnableToReadFile::class);
});

it('returns null mime type when head request fails', function () {
    Http::fake([
        'https://imagedelivery.net/*' => Http::response('', 500),
    ]);

    expect(adapter()->mimeType('missing.jpg')->mimeType())->toBeNull();
});

it('returns null last modified timestamp when head request fails', function () {
    Http::fake([
        'https://imagedelivery.net/*' => Http::response('', 500),
    ]);

    expect(adapter()->lastModified('missing.jpg')->lastModified())->toBeNull();
});

class FaultyTempFileAdapter extends CloudflareImagesAdapter
{
    protected function createTempFile()
    {
        return false;
    }
}

it('throws when a temporary file cannot be created during write', function () {
    expect(fn () => (new FaultyTempFileAdapter('token', 'acct', 'hash', 'public'))
        ->write('foo.jpg', 'contents', new Config)
    )->toThrow(\League\Flysystem\UnableToWriteFile::class);
});

function adapter() : CloudflareImagesAdapter
{
    return new CloudflareImagesAdapter('token', 'acct', 'hash', 'public');
}
