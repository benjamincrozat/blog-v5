<?php

namespace App\Jobs;

use App\Actions\Scrape;
use App\Actions\SelectProxy;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;

class ScrapeJob implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public string $url,
    ) {
        $this->queue = 'scraping';
    }

    public function handle() : void
    {
        $proxyServer = app(SelectProxy::class)->select();

        $webpage = app(Scrape::class)->scrape($this->url, $proxyServer);

        FetchJobData::dispatch($webpage);
    }
}
