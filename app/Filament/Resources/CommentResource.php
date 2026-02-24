<?php

namespace App\Filament\Resources;

use App\Models\Comment;
use Filament\Tables\Table;
use Filament\Schemas\Schema;
use Filament\Actions\EditAction;
use Filament\Resources\Resource;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Forms\Components\Select;
use Filament\Actions\DeleteBulkAction;
use Filament\Schemas\Components\Group;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\MarkdownEditor;
use App\Filament\Resources\CommentResource\Pages\EditComment;
use App\Filament\Resources\CommentResource\Pages\ListComments;
use App\Filament\Resources\CommentResource\Pages\CreateComment;

/**
 * Configures the comment resource Filament resource.
 */
class CommentResource extends Resource
{
    protected static ?string $model = Comment::class;

    protected static string|\UnitEnum|null $navigationGroup = 'Blog';

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-chat-bubble-oval-left';

    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema) : Schema
    {
        return $schema
            ->components([
                Group::make([
                    Select::make('user_id')
                        ->relationship('user', 'name')
                        ->required()
                        ->searchable()
                        ->label('Author'),

                    MarkdownEditor::make('content')
                        ->required(),
                ])
                    ->columnSpan([
                        'default' => 12,
                        'lg' => 8,
                    ]),

                Group::make([
                    Select::make('post_id')
                        ->relationship('post', 'title')
                        ->required()
                        ->label('Attached To Post'),

                    Select::make('parent_id')
                        ->relationship('parent', 'content')
                        ->label('In Reply To'),

                    DateTimePicker::make('modified_at')
                        ->timezone('Europe/Paris')
                        ->native(false)
                        ->label('Modification Date')
                        ->helperText('This is blank until the user updates the comment.'),
                ])
                    ->columnSpan([
                        'default' => 12,
                        'lg' => 4,
                    ]),
            ])
            ->columns(12);
    }

    public static function table(Table $table) : Table
    {
        return $table
            ->defaultSort('id', 'desc')
            ->columns([
                TextColumn::make('user.name')
                    ->searchable()
                    ->label('Author'),

                TextColumn::make('post.title')
                    ->searchable()
                    ->label('Post'),

                TextColumn::make('content')
                    ->searchable()
                    ->limit(50),

                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->label('Creation Date'),

                TextColumn::make('modified_at')
                    ->dateTime()
                    ->sortable()
                    ->label('Modification Date')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->recordActions([
                EditAction::make()
                    ->icon('')
                    ->button()
                    ->outlined()
                    ->size('xs'),

                DeleteAction::make()
                    ->icon('')
                    ->button()
                    ->outlined()
                    ->size('xs'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations() : array
    {
        return [];
    }

    public static function getPages() : array
    {
        return [
            'index' => ListComments::route('/'),
            'create' => CreateComment::route('/create'),
            'edit' => EditComment::route('/{record}/edit'),
        ];
    }

    public static function getGloballySearchableAttributes() : array
    {
        return ['user.name', 'content'];
    }

    public static function getGlobalSearchResultTitle(Model $record) : string
    {
        return strlen($record->content) > 50 ? substr($record->content, 0, 50) . '…' : $record->content;
    }

    public static function getGlobalSearchResultDetails(Model $record) : array
    {
        return ['Author' => $record->user->name];
    }
}
