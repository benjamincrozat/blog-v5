<?php

namespace App\Filament\Resources;

use App\Models\User;
use Filament\Tables\Table;
use Filament\Actions\Action;
use Filament\Schemas\Schema;
use Filament\Actions\EditAction;
use Filament\Resources\Resource;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\ImageColumn;
use Filament\Forms\Components\MarkdownEditor;
use App\Filament\Resources\UserResource\Pages\EditUser;
use App\Filament\Resources\UserResource\Pages\ListUsers;
use App\Filament\Resources\UserResource\Pages\CreateUser;

/**
 * Configures the user resource Filament resource.
 */
class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static string|\UnitEnum|null $navigationGroup = 'Community';

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-user';

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?int $navigationSort = 3;

    public static function form(Schema $schema) : Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255),

                TextInput::make('github_id')
                    ->required()
                    ->maxLength(255)
                    ->label('GitHub ID'),

                TextInput::make('github_login')
                    ->required()
                    ->maxLength(255)
                    ->label('GitHub'),

                TextInput::make('email')
                    ->email()
                    ->required()
                    ->maxLength(255),

                MarkdownEditor::make('biography')
                    ->columnSpanFull()
                    ->maxLength(65535),
            ])
            ->columns(1);
    }

    public static function table(Table $table) : Table
    {
        return $table
            ->defaultSort('id', 'desc')
            ->columns([
                ImageColumn::make('gravatar')
                    ->circular()
                    ->getStateUsing(fn (User $record) => $record->avatar),

                TextColumn::make('name'),

                TextColumn::make('github_login')
                    ->searchable()
                    ->label('GitHub'),

                TextColumn::make('email')
                    ->searchable(),

                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->label('Registration Date'),

                TextColumn::make('last_login_at')
                    ->dateTime()
                    ->sortable()
                    ->label('Last Login Date')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->recordActions([
                Action::make('impersonate')
                    ->label('Impersonate')
                    ->icon('')
                    ->button()
                    ->outlined()
                    ->color('gray')
                    ->size('xs')
                    ->action(function (User $record) {
                        session([
                            'impersonate.return' => request()->headers->get('referer') ?? request()->fullUrl(),
                        ]);

                        auth()->user()->impersonate($record);

                        return redirect()->route('home');
                    }),

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

    public static function getPages() : array
    {
        return [
            'index' => ListUsers::route('/'),
            'create' => CreateUser::route('/create'),
            'edit' => EditUser::route('/{record}/edit'),
        ];
    }

    public static function getGloballySearchableAttributes() : array
    {
        return ['name', 'github_id', 'github_login', 'email'];
    }

    public static function getGlobalSearchResultDetails(Model $record) : array
    {
        return [
            'Email' => $record->email,
        ];
    }
}
