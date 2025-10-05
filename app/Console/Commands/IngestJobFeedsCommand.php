<?php

namespace App\Console\Commands;

use App\Models\Job;
use App\Feed\FeedItem;
use App\Jobs\ScrapeJob;
use Illuminate\Console\Command;
use App\Actions\DiscoverFeedItems;
use Illuminate\Support\Collection;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(
    name: 'app:ingest-job-feeds',
    description: 'Fetch configured job feeds and enqueue scraping for new items',
)]
class IngestJobFeedsCommand extends Command
{
    protected $signature = 'app:ingest-job-feeds {feed? : Only ingest the feed with this name or URL} {--limit= : Override max items per run} {--dry-run : Do not dispatch jobs, only print what would be queued}';

    public function handle() : void
    {
        $feeds = (array) (config('job_feeds') ?? []);

        $feedFilter = (string) ($this->argument('feed') ?? '');
        $overrideLimit = $this->option('limit');
        $isDryRun = (bool) $this->option('dry-run');

        collect($feeds)
            ->filter(fn (array $feed) => (bool) ($feed['enabled'] ?? false))
            ->filter(function (array $feed) use ($feedFilter) {
                $name = (string) ($feed['name'] ?? 'unknown');
                $url = (string) ($feed['url'] ?? '');

                if ('' === $url) {
                    $this->warn("Skipped feed '$name' because URL is empty.");

                    return false;
                }

                if ('' === $feedFilter) {
                    return true;
                }

                return $this->matchesFilter($name, $url, $feedFilter);
            })
            ->each(function (array $feed) use ($overrideLimit, $isDryRun) {
                $name = (string) ($feed['name'] ?? 'unknown');
                $feedUrl = (string) ($feed['url'] ?? '');

                $limit = is_numeric($overrideLimit)
                    ? (int) $overrideLimit
                    : (int) ($feed['max_items_per_run'] ?? 20);

                $this->info("Discovering items from '$name'…");

                $items = app(DiscoverFeedItems::class)->discover($feedUrl);

                $toQueue = $items
                    ->reject(fn (FeedItem $item) => Job::query()->where('url', $item->url)->exists())
                    ->when($limit > 0, fn (Collection $collection) => $collection->take($limit));

                if ($isDryRun) {
                    $toQueue->each(
                        fn (FeedItem $item) => $this->line("Would queue: {$item->url}")
                    );
                } else {
                    $toQueue->each(
                        fn (FeedItem $item) => ScrapeJob::dispatch($item->url)
                    );
                }

                $this->info('Queued ' . $toQueue->count() . " new item(s) from '$name'.");
            });
    }

    private function matchesFilter(string $name, string $url, string $filter) : bool
    {
        return str_contains(mb_strtolower($name), mb_strtolower($filter))
            || str_contains(mb_strtolower($url), mb_strtolower($filter));
    }
}
