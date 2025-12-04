<?php

namespace App\Filament\Resources\Subscribers\Schemas;

use Spatie\Tags\Tag;
use DateTimeInterface;
use App\Models\Subscriber;
use Filament\Schemas\Schema;
use Illuminate\Support\Carbon;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Group;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Illuminate\Database\Eloquent\Builder;
use Filament\Infolists\Components\TextEntry;

class SubscriberForm
{
    public static function configure(Schema $schema) : Schema
    {
        return $schema
            ->components([
                Group::make([
                    TextInput::make('email')
                        ->label('Email address')
                        ->email()
                        ->required()
                        ->unique(ignoreRecord: true),

                    Select::make('tags')
                        ->relationship('tags', 'name', fn (Builder $query) => $query->orderBy('name'))
                        ->multiple()
                        ->preload()
                        ->searchable()
                        ->helperText('Tags let you segment subscribers for future niche newsletters.')
                        ->columnSpanFull()
                        ->createOptionForm([
                            TextInput::make('name')
                                ->label('Tag name')
                                ->required()
                                ->maxLength(50),
                        ])
                        ->createOptionUsing(fn (array $data) : int => Tag::findOrCreate($data['name'])->getKey()),
                ])
                    ->key('subscriber-section')
                    ->columnSpan([
                        'default' => 12,
                        'lg' => 8,
                    ]),

                Section::make('Status')
                    ->schema([
                        TextEntry::make('confirmed_at')
                            ->state(fn (?Subscriber $record) => static::formatTimestamp($record?->confirmed_at) ?? 'Not confirmed yet.')
                            ->label('Confirmation Date'),

                        TextEntry::make('confirmation_sent_at')
                            ->state(fn (?Subscriber $record) => static::formatTimestamp($record?->confirmation_sent_at) ?? 'Never')
                            ->label('Confirmation Sent Date'),

                        TextEntry::make('created_at')
                            ->state(fn (?Subscriber $record) => static::formatTimestamp($record?->created_at) ?? '—')
                            ->label('Creation Date'),
                    ])
                    ->key('status-section')
                    ->columnSpan([
                        'default' => 12,
                        'lg' => 4,
                    ]),
            ])
            ->columns(12);
    }

    protected static function formatTimestamp(?DateTimeInterface $timestamp) : ?string
    {
        if (null === $timestamp) {
            return null;
        }

        return Carbon::parse($timestamp)
            ->timezone(config('app.timezone'))
            ->toDayDateTimeString();
    }
}
