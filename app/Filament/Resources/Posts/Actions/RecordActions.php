<?php

namespace App\Filament\Resources\Posts\Actions;

use App\Models\Post;
use Illuminate\Support\Js;
use Filament\Actions\Action;

/**
 * Provides reusable record actions Filament actions.
 */
class RecordActions
{
    public static function configure() : array
    {
        return [
            Action::make('open')
                ->label('Open')
                ->icon('heroicon-o-arrow-top-right-on-square')
                ->url(fn (Post $record) => route('posts.show', $record), shouldOpenInNewTab: true),

            Action::make('copy_url')
                ->label('Copy URL')
                ->icon('heroicon-o-link')
                ->alpineClickHandler(fn (Post $record) => 'window.navigator.clipboard.writeText(' . Js::from(route('posts.show', $record)) . ')'),

            Action::make('copy')
                ->label('Copy as Markdown')
                ->icon('heroicon-o-clipboard-document')
                ->alpineClickHandler(
                    fn (Post $record) => strtr(
                        <<<'JS'
(async () => {
    const response = await fetch(__URL__, {
        headers: {
            Accept: 'text/plain',
            'X-Requested-With': 'XMLHttpRequest',
        },
    });

    if (! response.ok) {
        throw new Error('Unable to fetch post markdown.');
    }

    await window.navigator.clipboard.writeText(await response.text());
})().catch((error) => console.error(error))
JS,
                        ['__URL__' => Js::from(route('admin.posts.markdown', $record))]
                    )
                ),

            Action::make('check_in_gsc')
                ->label('Check in GSC')
                ->icon('heroicon-o-chart-bar')
                ->url(function (Post $record) {
                    $domain = preg_replace('/https?:\/\//', '', config('app.url'));

                    return "https://search.google.com/search-console/performance/search-analytics?resource_id=sc-domain%3A$domain&breakdown=query&page=!" . rawurlencode(route('posts.show', $record));
                }, shouldOpenInNewTab: true),
        ];
    }
}
