<?php

namespace App\Filesystem;

use League\Flysystem\Config;
use Illuminate\Support\Facades\Http;
use League\Flysystem\FileAttributes;
use League\Flysystem\UnableToCopyFile;
use League\Flysystem\UnableToMoveFile;
use League\Flysystem\UnableToReadFile;
use League\Flysystem\FilesystemAdapter;
use League\Flysystem\UnableToWriteFile;
use League\Flysystem\UnableToDeleteFile;
use League\Flysystem\UnableToCreateDirectory;
use League\Flysystem\UnableToDeleteDirectory;
use League\Flysystem\UnableToRetrieveMetadata;
use League\Flysystem\InvalidVisibilityProvided;

class CloudflareImagesAdapter implements FilesystemAdapter
{
    protected bool $useFakeStorage;

    /** @var array<string, array{contents:string, mime_type:string, timestamp:int}> */
    protected array $fakeStorage = [];

    public function __construct(
        protected string $token,
        protected string $accountId,
        protected string $accountHash,
        protected string $variant = 'public',
    ) {
        $this->useFakeStorage = blank($this->token) || blank($this->accountId) || blank($this->accountHash);
    }

    /* @inheritdoc */
    public function fileExists(string $path) : bool
    {
        if ($this->usingFakeStorage()) {
            return array_key_exists($path, $this->fakeStorage);
        }

        $response = Http::withToken($this->token)
            ->get($this->api("images/v1/{$path}"));

        return $response->ok();
    }

    /* @inheritdoc */
    public function directoryExists(string $path) : bool
    {
        return false; // Cloudflare Images has no directories concept.
    }

    /* @inheritdoc */
    public function write(string $path, string $contents, Config $config) : void
    {
        if ($this->usingFakeStorage()) {
            $this->storeFakeFile($path, $contents, $config->get('mime_type'));

            return;
        }

        $tmp = tmpfile();

        if (false === $tmp) {
            throw UnableToWriteFile::atLocation($path, 'Unable to create temporary file.');
        }

        fwrite($tmp, $contents);

        rewind($tmp);

        $this->upload($path, $tmp);

        fclose($tmp);
    }

    /* @inheritdoc */
    public function writeStream(string $path, $contents, Config $config) : void
    {
        if ($this->usingFakeStorage()) {
            $data = $this->streamToString($contents);

            $this->storeFakeFile($path, $data, $config->get('mime_type'));

            return;
        }

        $this->upload($path, $contents);
    }

    protected function upload(string $path, $resource) : void
    {
        if ($this->usingFakeStorage()) {
            $this->storeFakeFile($path, $this->streamToString($resource));

            return;
        }

        $response = Http::withToken($this->token)
            ->asMultipart()
            ->attach('file', $resource, basename($path))
            ->post($this->api('images/v1'), [
                // Store the desired path as the custom ID so we can reference it later.
                'id' => $path,
                'requireSignedURLs' => 'false',
            ]);

        if (! $response->ok()) {
            throw UnableToWriteFile::atLocation($path, $response->body());
        }
    }

    /* @inheritdoc */
    public function read(string $path) : string
    {
        if ($this->usingFakeStorage()) {
            return $this->getFakeFile($path)['contents'];
        }

        $response = Http::get($this->getUrl($path));

        if (! $response->ok()) {
            throw UnableToReadFile::fromLocation($path, $response->status());
        }

        return $response->body();
    }

    /* @inheritdoc */
    public function readStream(string $path)
    {
        if ($this->usingFakeStorage()) {
            $stream = fopen('php://temp', 'rb+');

            if (false === $stream) {
                throw UnableToReadFile::fromLocation($path, 'Unable to create temporary stream.');
            }

            fwrite($stream, $this->getFakeFile($path)['contents']);
            rewind($stream);

            return $stream;
        }

        $response = Http::withOptions(['stream' => true])
            ->get($this->getUrl($path));

        if (! $response->ok()) {
            throw UnableToReadFile::fromLocation($path, $response->status());
        }

        return $response->toPsrResponse()->getBody()->detach();
    }

    /* @inheritdoc */
    public function delete(string $path) : void
    {
        if ($this->usingFakeStorage()) {
            unset($this->fakeStorage[$path]);

            return;
        }

        $response = Http::withToken($this->token)
            ->delete($this->api("images/v1/{$path}"));

        if (! $response->ok()) {
            throw UnableToDeleteFile::atLocation($path, $response->body());
        }
    }

    /* @inheritdoc */
    public function deleteDirectory(string $path) : void
    {
        throw UnableToDeleteDirectory::atLocation($path, 'Directories are not supported by Cloudflare Images.');
    }

    /* @inheritdoc */
    public function createDirectory(string $path, Config $config) : void
    {
        throw UnableToCreateDirectory::atLocation($path, 'Directories are not supported by Cloudflare Images.');
    }

    /* @inheritdoc */
    public function setVisibility(string $path, string $visibility) : void
    {
        throw InvalidVisibilityProvided::withVisibility($visibility, 'public');
    }

    /* @inheritdoc */
    public function visibility(string $path) : FileAttributes
    {
        if ($this->usingFakeStorage()) {
            return new FileAttributes($path, null, 'public');
        }

        return new FileAttributes($path, null, 'public');
    }

    /* @inheritdoc */
    public function mimeType(string $path) : FileAttributes
    {
        if ($this->usingFakeStorage()) {
            $file = $this->getFakeFile($path);

            return new FileAttributes($path, null, 'public', null, $file['mime_type']);
        }

        $headers = $this->getHeaders($path);

        return new FileAttributes($path, null, 'public', null, $headers['content-type'] ?? null);
    }

    /* @inheritdoc */
    public function lastModified(string $path) : FileAttributes
    {
        if ($this->usingFakeStorage()) {
            $file = $this->getFakeFile($path);

            return new FileAttributes($path, null, 'public', $file['timestamp']);
        }

        $headers = $this->getHeaders($path);

        $timestamp = isset($headers['last-modified']) ? strtotime($headers['last-modified']) ?: null : null;

        return new FileAttributes($path, null, 'public', $timestamp);
    }

    /* @inheritdoc */
    public function fileSize(string $path) : FileAttributes
    {
        if ($this->usingFakeStorage()) {
            $file = $this->getFakeFile($path);

            return new FileAttributes($path, strlen($file['contents']));
        }

        $headers = $this->getHeaders($path);

        $size = isset($headers['content-length']) ? (int) $headers['content-length'] : null;

        if (null === $size) {
            throw UnableToRetrieveMetadata::fileSize($path, 'Content-Length header missing.');
        }

        return new FileAttributes($path, $size);
    }

    /* @inheritdoc */
    public function listContents(string $path, bool $deep) : iterable
    {
        if ($this->usingFakeStorage()) {
            foreach ($this->fakeStorage as $key => $file) {
                yield new FileAttributes($key, strlen($file['contents']), 'public', $file['timestamp'], $file['mime_type']);
            }

            return;
        }

        return []; // Listing is not required for now.
    }

    /* @inheritdoc */
    public function move(string $source, string $destination, Config $config) : void
    {
        throw UnableToMoveFile::because($source, $destination, 'Cloudflare Images does not support moving files.');
    }

    /* @inheritdoc */
    public function copy(string $source, string $destination, Config $config) : void
    {
        throw UnableToCopyFile::because($source, $destination, 'Cloudflare Images does not support copying files.');
    }

    /**
     * Build full API URL for the given path.
     */
    protected function api(string $endpoint) : string
    {
        return sprintf('https://api.cloudflare.com/client/v4/accounts/%s/%s', $this->accountId, ltrim($endpoint, '/'));
    }

    /**
     * Public URL used by Laravel's `Storage::url()`.
     */
    public function getUrl(string $path) : string
    {
        if ($this->usingFakeStorage()) {
            return sprintf('https://cloudflare-images.test/%s', trim($path, '/'));
        }

        return sprintf('https://imagedelivery.net/%s/%s/%s', $this->accountHash, trim($path, '/'), $this->variant);
    }

    /**
     * Retrieve HTTP headers for the given image path.
     *
     * @return array<string,string>
     */
    protected function getHeaders(string $path) : array
    {
        if ($this->usingFakeStorage()) {
            $file = $this->getFakeFile($path);

            return [
                'content-length' => (string) strlen($file['contents']),
                'content-type' => $file['mime_type'],
                'last-modified' => gmdate('D, d M Y H:i:s \G\M\T', $file['timestamp']),
            ];
        }

        $response = Http::withoutVerifying()->head($this->getUrl($path));

        if (! $response->ok()) {
            return [];
        }

        $headers = array_change_key_case($response->headers(), CASE_LOWER);

        // Flatten header values: take the first element of each array.
        foreach ($headers as $key => $value) {
            if (is_array($value)) {
                $headers[$key] = $value[0] ?? null;
            }
        }

        return $headers;
    }

    protected function usingFakeStorage() : bool
    {
        return $this->useFakeStorage;
    }

    protected function storeFakeFile(string $path, string $contents, ?string $mimeType = null) : void
    {
        $this->fakeStorage[$path] = [
            'contents' => $contents,
            'mime_type' => $mimeType ?? $this->guessMimeType($path, $contents),
            'timestamp' => time(),
        ];
    }

    /**
     * @return array{contents:string, mime_type:string, timestamp:int}
     */
    protected function getFakeFile(string $path) : array
    {
        if (! array_key_exists($path, $this->fakeStorage)) {
            throw UnableToReadFile::fromLocation($path, 'File does not exist in fake storage.');
        }

        return $this->fakeStorage[$path];
    }

    protected function guessMimeType(string $path, string $contents) : string
    {
        $finfo = new \finfo(FILEINFO_MIME_TYPE);

        $mimeType = $finfo->buffer($contents) ?: null;

        if ($mimeType) {
            return $mimeType;
        }

        $extension = pathinfo($path, PATHINFO_EXTENSION);

        return match (strtolower($extension)) {
            'jpg', 'jpeg' => 'image/jpeg',
            'png' => 'image/png',
            'gif' => 'image/gif',
            default => 'application/octet-stream',
        };
    }

    protected function streamToString($resource) : string
    {
        if (is_resource($resource)) {
            $meta = stream_get_meta_data($resource);

            if (($meta['seekable'] ?? false) === true) {
                rewind($resource);
            }

            $data = stream_get_contents($resource);

            if (false === $data) {
                throw UnableToWriteFile::atLocation('', 'Unable to read from resource.');
            }

            return $data;
        }

        return (string) $resource;
    }
}
