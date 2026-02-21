<?php

namespace App\Filament\Resources\Posts\Actions;

use App\Models\Post;
use App\Jobs\ReviewPost;
use App\Jobs\RecommendPosts;
use Filament\Actions\BulkAction;
use Illuminate\Support\Collection;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Notifications\Notification;
use Filament\Actions\ForceDeleteBulkAction;

/**
 * Defines the BulkActions implementation.
 */
class BulkActions
{
    public static function configure() : array
    {
        return [
            BulkAction::make('Refresh recommendations')
                ->action(function (Collection $records) {
                    $records->each(fn (Post $record) => RecommendPosts::dispatch($record));

                    Notification::make()
                        ->title(trans_choice('A job has been queued to refresh the recommendations.|Jobs have been queued to refresh the recommendations.', $records->count()))
                        ->success()
                        ->send();
                })
                ->icon('heroicon-o-arrow-path'),

            BulkAction::make('Ask for editor review')
                ->modalSubmitActionLabel('Review')
                ->action(function (Collection $records) {
                    $records->each(fn (Post $record) => ReviewPost::dispatch($record));

                    Notification::make()
                        ->title(trans_choice('The post has been queued for review.|The posts have been queued for review.', $records->count()))
                        ->success()
                        ->send();
                })
                ->icon('heroicon-o-document-text'),

            DeleteBulkAction::make(),

            ForceDeleteBulkAction::make(),

            RestoreBulkAction::make(),
        ];
    }
}
