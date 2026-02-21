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
        ]);
    }
}
