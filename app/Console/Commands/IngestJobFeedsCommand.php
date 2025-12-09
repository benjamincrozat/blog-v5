<?php

namespace App\Console\Commands;

use App\Models\Job;
use App\Feed\FeedItem;
use App\Jobs\ScrapeJob;
use Illuminate\Console\Command;
use App\Actions\DiscoverFeedItems;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(
    name: 'app:ingest-job-feeds',
    description: 'Fetch configured job feeds and enqueue scraping for new items',
)]
class IngestJobFeedsCommand extends Command
{
    protected $signature = 'app:ingest-job-feeds {feed? : Only ingest the feed with this name or URL} {--dry-run : Do not dispatch jobs, only print what would be queued}';

    public function handle() : void
    {
        $config = config('job_feeds') ?? [];

        if (array_is_list($config)) {
            $feeds = $config;
            $globalLimit = 10;
            $perSourceLimit = 2;
        } else {
            $feeds = (array) ($config['feeds'] ?? []);
            $globalLimit = (int) ($config['global_limit'] ?? 10);
            $perSourceLimit = (int) ($config['per_source_limit'] ?? 2);
        }

        $feedFilter = (string) ($this->argument('feed') ?? '');
        $isDryRun = (bool) $this->option('dry-run');

        $eligibleFeeds = collect($feeds)
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
            ->shuffle()
            ->values();

        $queuedItems = collect();
        $queuedByFeed = [];
        $queuedUrls = [];

        foreach ($eligibleFeeds as $feed) {
            if ($queuedItems->count() >= $globalLimit) {
                break;
            }

            $name = (string) ($feed['name'] ?? 'unknown');
            $feedUrl = (string) ($feed['url'] ?? '');

            $this->info("Discovering items from '$name'…");

            $items = app(DiscoverFeedItems::class)->discover($feedUrl);

            $remainingForRun = $globalLimit - $queuedItems->count();
            $maxFromThisFeed = min($perSourceLimit, $remainingForRun);

            if ($maxFromThisFeed <= 0) {
                break;
            }

            $selectedFromFeed = $items
                ->reject(function (FeedItem $item) use (&$queuedUrls) {
                    return in_array($item->url, $queuedUrls, true) || Job::query()->where('url', $item->url)->exists();
                })
                ->take($maxFromThisFeed)
                ->values();

            if ($selectedFromFeed->isEmpty()) {
                $this->info("Queued 0 new item(s) from '$name'.");

                continue;
            }

            $selectedFromFeed->each(function (FeedItem $item) use (&$queuedItems, &$queuedUrls) {
                $queuedItems->push($item);
                $queuedUrls[] = $item->url;
            });

            $queuedByFeed[$name] = ($queuedByFeed[$name] ?? 0) + $selectedFromFeed->count();

            if ($queuedItems->count() >= $globalLimit) {
                break;
            }
        }

        if ($isDryRun) {
            $queuedItems->each(
                fn (FeedItem $item) => $this->line("Would queue: {$item->url}")
            );
        } else {
            $queuedItems->each(
                fn (FeedItem $item) => ScrapeJob::dispatch($item->url)
            );
        }

        foreach ($queuedByFeed as $feedName => $count) {
            $this->info('Queued ' . $count . " new item(s) from '$feedName'.");
        }
    }

    protected function matchesFilter(string $name, string $url, string $filter) : bool
    {
        return str_contains(mb_strtolower($name), mb_strtolower($filter))
            || str_contains(mb_strtolower($url), mb_strtolower($filter));
    }
}
