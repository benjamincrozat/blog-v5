<?php

namespace App\Filament\Resources\Posts;

use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Filters\TrashedFilter;

/**
 * Defines the Filters implementation.
 */
class Filters
{
    public static function configure() : array
    {
        return [
            TernaryFilter::make('image_path')
                ->nullable()
                ->label('Image')
                ->placeholder('Both')
                ->trueLabel('With image')
                ->falseLabel('Without image')
                ->queries(
                    blank: fn (Builder $query) => $query,
                    true: fn (Builder $query) => $query->whereNotNull('image_path'),
                    false: fn (Builder $query) => $query->whereNull('image_path'),
                ),

            SelectFilter::make('link_association')
                ->label('Link association')
                ->options([
                    'with_link' => 'With link',
                    'without_link' => 'Without link',
                ])
                ->query(fn (Builder $query, array $data) => match ($data['value']) {
                    'with_link' => $query->whereHas('link'),
                    'without_link' => $query->whereDoesntHave('link'),
                    default => $query,
                }),

            TernaryFilter::make('published_at')
                ->nullable()
                ->label('Published status')
                ->placeholder('Both')
                ->trueLabel('Published')
                ->falseLabel('Draft')
                ->queries(
                    blank: fn (Builder $query) => $query,
                    true: fn (Builder $query) => $query->whereNotNull('published_at'),
                    false: fn (Builder $query) => $query->whereNull('published_at'),
                ),

            TernaryFilter::make('updated_stale')
                ->nullable()
                ->label('Updated 1+ year ago')
                ->placeholder('Both')
                ->trueLabel('Yes')
                ->falseLabel('No')
                ->queries(
                    blank: fn (Builder $query) => $query,
                    true: fn (Builder $query) => $query->where(function (Builder $query) {
                        $oneYearAgo = now()->subYear();

                        $query
                            ->whereDate('modified_at', '<=', $oneYearAgo)
                            ->orWhere(
                                fn (Builder $query) => $query
                                    ->whereNull('modified_at')
                                    ->whereDate('published_at', '<=', $oneYearAgo)
                            );
                    }),
                    false: fn (Builder $query) => $query->where(function (Builder $query) {
                        $oneYearAgo = now()->subYear();

                        $query->where(
                            fn (Builder $query) => $query
                                ->whereNotNull('modified_at')
                                ->whereDate('modified_at', '>', $oneYearAgo)
                        )->orWhere(
                            fn (Builder $query) => $query
                                ->whereNull('modified_at')
                                ->whereDate('published_at', '>', $oneYearAgo)
                        );
                    }),
                ),

            TrashedFilter::make(),
        ];
    }
}
