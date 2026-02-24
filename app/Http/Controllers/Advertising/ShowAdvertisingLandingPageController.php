<?php

namespace App\Http\Controllers\Advertising;

use App\Models\Metric;
use Illuminate\View\View;
use Illuminate\Support\Number;
use App\Http\Controllers\Controller;

/**
 * Defines the ShowAdvertisingLandingPageController implementation.
 */
class ShowAdvertisingLandingPageController extends Controller
{
    public function __invoke() : View
    {
        $stickyCarouselExampleAds = [
            [
                'icon' => '<div class="size-8 bg-red-600"></div>',
                'title' => 'Featured on every page',
                'description' => 'Each ad is shown for 8 seconds.',
                'cta' => 'Example call to action',
                'url' => 'https://example.com',
            ],
            [
                'icon' => '<div class="size-8 bg-green-600"></div>',
                'title' => 'Your ad is beautiful',
                'description' => 'People will read it and eventually click on it.',
                'cta' => 'Another call to action',
                'url' => 'https://example.com',
            ],
        ];

        return view('advertise', [
            'views' => Number::format(
                Metric::query()->where('key', 'views')->value('value') ?? 0
            ),
            'sessions' => Number::format(
                Metric::query()->where('key', 'sessions')->value('value') ?? 0
            ),
            'desktop' => Number::format(
                Metric::query()->where('key', 'platform_desktop')->value('value') ?? 0, 0
            ),
            'stickyCarouselExampleAds' => $stickyCarouselExampleAds,
        ]);
    }
}
