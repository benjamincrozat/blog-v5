<?php

use App\Jobs\ScrapeJob;
use App\Scraper\Webpage;
use App\Jobs\FetchJobData;
use Mockery\MockInterface;
use Illuminate\Support\Facades\Bus;
use App\Actions\Scrape as ScrapeAction;
use App\Actions\SelectProxy as SelectProxyAction;

it('uses the scraping queue by default', function () {
    expect((new ScrapeJob('https://example.com'))->queue)->toBe('scraping');
});

it('scrapes the url with a proxy and dispatches the fetch job', function () {
    Bus::fake();

    $proxy = 'proxy.smart:8080';
    $webpage = new Webpage('https://example.com/post', null, 'Example', '<p>Hi</p>');

    $selectProxyAction = mock(SelectProxyAction::class, function (MockInterface $mock) use ($proxy) {
        $mock->shouldReceive('select')
            ->once()
            ->andReturn($proxy);
    });

    $scrapeAction = mock(ScrapeAction::class, function (MockInterface $mock) use ($proxy, $webpage) {
        $mock->shouldReceive('scrape')
            ->once()
            ->with('https://example.com', $proxy)
            ->andReturn($webpage);
    });

    app()->instance(SelectProxyAction::class, $selectProxyAction);
    app()->instance(ScrapeAction::class, $scrapeAction);

    (new ScrapeJob('https://example.com'))->handle();

    Bus::assertDispatched(FetchJobData::class, function (FetchJobData $job) use ($webpage) {
        return $job->webpage === $webpage;
    });
});
