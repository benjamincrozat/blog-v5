<?php

namespace App\Jobs;

use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;

/**
 * Queues track event work.
 */
class TrackEvent implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public string $name,
        public array $meta,
        public string $url,
        public string $ip,
        public string $userAgent,
        public string $acceptLanguage,
        public string $referrer,
    ) {}

    public function handle() : void
    {
        app(\App\Actions\TrackEvent::class)->track(
            $this->name,
            $this->meta,
            $this->url,
            $this->ip,
            $this->userAgent,
            $this->acceptLanguage,
            $this->referrer,
        );
    }
}
