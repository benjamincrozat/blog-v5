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
    public function __construct(
        protected string $token,
        protected string $accountId,
        protected string $accountHash,
        protected string $variant = 'public',
    ) {}

    /* @inheritdoc */
    public function fileExists(string $path) : bool
    {
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
        $tmp = $this->createTempFile();

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
        $this->upload($path, $contents);
    }

    protected function upload(string $path, $resource) : void
    {
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
        $response = Http::get($this->getUrl($path));

        if (! $response->ok()) {
            throw UnableToReadFile::fromLocation($path, $response->status());
        }

        return $response->body();
    }

    /* @inheritdoc */
    public function readStream(string $path)
    {
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
        return new FileAttributes($path, null, 'public');
    }

    /* @inheritdoc */
    public function mimeType(string $path) : FileAttributes
    {
        $headers = $this->getHeaders($path);

        return new FileAttributes($path, null, 'public', null, $headers['content-type'] ?? null);
    }

    /* @inheritdoc */
    public function lastModified(string $path) : FileAttributes
    {
        $headers = $this->getHeaders($path);

        $timestamp = isset($headers['last-modified']) ? strtotime($headers['last-modified']) ?: null : null;

        return new FileAttributes($path, null, 'public', $timestamp);
    }

    /* @inheritdoc */
    public function fileSize(string $path) : FileAttributes
    {
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
     * Create a temporary file resource.
     *
     * @return resource|false
     */
    protected function createTempFile()
    {
        return tmpfile();
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
        return sprintf('https://imagedelivery.net/%s/%s/%s', $this->accountHash, trim($path, '/'), $this->variant);
    }

    /**
     * Retrieve HTTP headers for the given image path.
     *
     * @return array<string,string>
     */
    protected function getHeaders(string $path) : array
    {
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
}
