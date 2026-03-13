<?php

namespace Tests\Feature\App\Filesystem;

use App\Filesystem\CloudflareImagesAdapter;

class FaultyTempFileAdapter extends CloudflareImagesAdapter
{
    protected function createTempFile()
    {
        return false;
    }
}
