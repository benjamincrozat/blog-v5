<?php

namespace App\Jobs;

use App\Scraper\Webpage;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;

class FetchJobData implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Webpage $webpage,
    ) {}

    public function handle() : void
    {
        $data = app(\App\Actions\Jobs\FetchJobData::class)->fetch($this->webpage);

        $data['company'] = array_merge([
            'name' => null,
            'url' => null,
            'logo' => null,
            'about' => null,
        ], $data['company'] ?? []);

        CreateJob::dispatch($this->webpage, $this->toObject($data));
    }

    private function toObject(mixed $value) : mixed
    {
        if (is_array($value)) {
            $obj = new \stdClass;

            foreach ($value as $key => $item) {
                $obj->{$key} = $this->toObject($item);
            }

            return $obj;
        }

        return $value;
    }
}
