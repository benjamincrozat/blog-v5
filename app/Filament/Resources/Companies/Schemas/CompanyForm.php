<?php

namespace App\Filament\Resources\Companies\Schemas;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Group;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\MarkdownEditor;

class CompanyForm
{
    public static function configure(Schema $schema) : Schema
    {
        return $schema
            ->components([
                Group::make([
                    TextInput::make('name')
                        ->required(),

                    MarkdownEditor::make('about')
                        ->columnSpanFull(),
                ])
                    ->columnSpan([
                        'default' => 12,
                        'lg' => 8,
                    ]),

                Group::make([
                    TextInput::make('slug')
                        ->required(),

                    TextInput::make('url')
                        ->url()
                        ->label('URL'),

                    TextInput::make('logo')
                        ->nullable(),
                ])
                    ->columnSpan([
                        'default' => 12,
                        'lg' => 4,
                    ]),
            ])
            ->columns(12);
    }
}
