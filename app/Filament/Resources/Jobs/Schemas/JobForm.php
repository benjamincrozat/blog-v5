<?php

namespace App\Filament\Resources\Jobs\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Group;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\CodeEditor;

class JobForm
{
    public static function configure(Schema $schema) : Schema
    {
        return $schema
            ->components([
                Group::make([
                    TextInput::make('title')
                        ->required(),

                    CodeEditor::make('locations')
                        ->json()
                        ->nullable()
                        ->formatStateUsing(function ($state) {
                            return is_array($state)
                                ? json_encode($state, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)
                                : (is_string($state) ? $state : null);
                        }),

                    CodeEditor::make('technologies')
                        ->json()
                        ->nullable()
                        ->formatStateUsing(function ($state) {
                            return is_array($state)
                                ? json_encode($state, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)
                                : (is_string($state) ? $state : null);
                        }),

                    Textarea::make('description')
                        ->required()
                        ->columnSpanFull(),

                    CodeEditor::make('perks')
                        ->json()
                        ->nullable()
                        ->formatStateUsing(function ($state) {
                            return is_array($state)
                                ? json_encode($state, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)
                                : (is_string($state) ? $state : null);
                        }),

                    CodeEditor::make('interview_process')
                        ->json()
                        ->nullable()
                        ->formatStateUsing(function ($state) {
                            return is_array($state)
                                ? json_encode($state, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)
                                : (is_string($state) ? $state : null);
                        }),
                ])
                    ->columnSpan([
                        'default' => 12,
                        'lg' => 8,
                    ]),

                Group::make([
                    Select::make('company_id')
                        ->relationship('company', 'name')
                        ->required()
                        ->searchable(),

                    TextInput::make('url')
                        ->url()
                        ->required()
                        ->label('URL'),

                    TextInput::make('source')
                        ->required(),

                    TextInput::make('language')
                        ->required(),

                    TextInput::make('slug')
                        ->required(),

                    Select::make('setting')
                        ->options([
                            'fully-remote' => 'Fully-remote',
                            'hybrid' => 'Hybrid',
                            'on-site' => 'On-site',
                        ])
                        ->required(),

                    TextInput::make('min_salary')
                        ->numeric()
                        ->label('Minimum Salary'),

                    TextInput::make('max_salary')
                        ->numeric()
                        ->label('Maximum Salary'),

                    TextInput::make('currency'),

                    Toggle::make('equity')
                        ->required(),
                ])->columnSpan([
                    'default' => 12,
                    'lg' => 4,
                ]),
            ])
            ->columns(12);
    }
}
