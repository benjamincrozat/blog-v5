<?php

namespace App\Filament\Resources;

use App\Models\Link;
use Filament\Tables\Table;
use Filament\Actions\Action;
use Filament\Schemas\Schema;
use App\Jobs\CreatePostForLink;
use Filament\Actions\BulkAction;
use Filament\Actions\EditAction;
use Filament\Resources\Resource;
use Filament\Actions\ActionGroup;
use Filament\Actions\DeleteAction;
use Illuminate\Support\Collection;
use App\Notifications\LinkDeclined;
use Filament\Actions\BulkActionGroup;
use Filament\Forms\Components\Select;
use Filament\Actions\DeleteBulkAction;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\DateTimePicker;
use App\Filament\Resources\LinkResource\Pages\EditLink;
use App\Filament\Resources\LinkResource\Pages\ListLinks;
use App\Filament\Resources\LinkResource\Pages\CreateLink;

class LinkResource extends Resource
{
    protected static ?string $model = Link::class;

    protected static string|\UnitEnum|null $navigationGroup = 'Community';

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-link';

    protected static ?string $recordTitleAttribute = 'title';

    protected static ?int $navigationSort = 3;

    public static function getNavigationBadge() : ?string
    {
        return static::getModel()::query()->pending()->count();
    }

    public static function getNavigationBadgeTooltip() : ?string
    {
        return 'Links waiting for approval.';
    }

    public static function form(Schema $schema) : Schema
    {
        return $schema
            ->components([
                Select::make('user_id')
                    ->relationship('user', 'name')
                    ->required()
                    ->searchable()
                    ->columnSpanFull()
                    ->label('Sender'),

                Select::make('post_id')
                    ->relationship('post', 'title')
                    ->searchable()
                    ->columnSpanFull()
                    ->label('Post')
                    ->helperText("Any link can be associated with a post. Usually, they're AI-generated."),

                TextInput::make('url')
                    ->required()
                    ->url()
                    ->maxLength(255)
                    ->label('URL')
                    ->columnSpanFull(),

                TextInput::make('image_url')
                    ->url()
                    ->label('Image URL')
                    ->columnSpanFull(),

                TextInput::make('author')
                    ->required()
                    ->maxLength(255)
                    ->columnSpanFull(),

                TextInput::make('title')
                    ->required()
                    ->maxLength(255)
                    ->columnSpanFull(),

                Textarea::make('description')
                    ->maxLength(65535)
                    ->columnSpanFull(),

                Textarea::make('notes')
                    ->maxLength(65535)
                    ->columnSpanFull(),

                DateTimePicker::make('is_approved')
                    ->timezone('Europe/Paris')
                    ->native(false)
                    ->label('Approved At'),

                DateTimePicker::make('is_declined')
                    ->timezone('Europe/Paris')
                    ->native(false)
                    ->label('Declined At'),
            ]);
    }

    public static function table(Table $table) : Table
    {
        return $table
            ->defaultSort('id', 'desc')
            ->columns([
                ImageColumn::make('image_url')
                    ->imageWidth(107)
                    ->imageHeight(80)
                    ->label('Image'),

                TextColumn::make('title')
                    ->searchable()
                    ->limit(50),

                TextColumn::make('url')
                    ->url(fn (Link $record) => $record->url, shouldOpenInNewTab: true)
                    ->searchable()
                    ->limit(40)
                    ->label('URL'),

                TextColumn::make('user.name')
                    ->searchable()
                    ->label('Sender'),

                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state) : string => match ($state) {
                        'Approved' => 'success',
                        'Declined' => 'danger',
                        default => 'gray',
                    })
                    ->getStateUsing(function (Model $record) {
                        if ($record->is_approved) {
                            return 'Approved';
                        }

                        if ($record->is_declined) {
                            return 'Declined';
                        }

                        return 'Pending';
                    })
                    ->label('Status'),

                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->label('Submitted Date'),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'approved' => 'Approved',
                        'declined' => 'Declined',
                        'pending' => 'Pending',
                    ])
                    ->query(fn (Builder $query, array $data) => match ($data['value']) {
                        'approved' => $query->approved(),
                        'declined' => $query->declined(),
                        'pending' => $query->pending(),
                        default => $query,
                    })
                    ->default('pending'),
            ])
            ->recordActions([
                ActionGroup::make([
                    Action::make('open')
                        ->url(fn (Link $record) => $record->url, shouldOpenInNewTab: true)
                        ->icon('heroicon-o-arrow-top-right-on-square'),

                    Action::make('approve')
                        ->schema([
                            Textarea::make('notes')
                                ->helperText('These notes will help when generating the small companion article designed to entice readers to click.'),
                        ])
                        ->modalHeading('Approve Link')
                        ->action(function (array $data, Link $record) {
                            $record->approve($data['notes']);

                            if ($record->post_id) {
                                Notification::make()
                                    ->title('The link has been approved.')
                                    ->success()
                                    ->send();
                            } else {
                                CreatePostForLink::dispatch($record);

                                Notification::make()
                                    ->title('The link has been approved and a post is being created.')
                                    ->success()
                                    ->send();
                            }
                        })
                        ->modalSubmitActionLabel('Approve and generate post')
                        ->modalCancelActionLabel('Approve without post')
                        ->modalCancelAction(function (Action $action) {
                            $action->action(function (Link $record) {
                                $record->approve();

                                Notification::make()
                                    ->title('The link has been approved.')
                                    ->success()
                                    ->send();
                            });
                        })
                        ->hidden(fn (Link $record) => $record->isApproved())
                        ->icon('heroicon-o-check')
                        ->label('Approve'),

                    Action::make('decline')
                        ->schema([
                            Textarea::make('reason')
                                ->nullable(),
                        ])
                        ->action(function (Link $record, array $data) {
                            $record->decline($data['reason']);

                            $record->user->notify(new LinkDeclined($record, $data['reason']));

                            Notification::make()
                                ->title('The link has been declined.')
                                ->success()
                                ->send();
                        })
                        ->modalSubmitActionLabel('Decline')
                        ->hidden(fn (Link $record) => $record->isDeclined())
                        ->color('danger')
                        ->icon('heroicon-o-x-circle'),

                    Action::make('generate_post')
                        ->action(function (Link $record, array $data) {
                            $record->update([
                                'notes' => $data['notes'],
                            ]);

                            CreatePostForLink::dispatch($record);

                            Notification::make()
                                ->title('A new post is being regenerated.')
                                ->success()
                                ->send();
                        })
                        ->schema([
                            Textarea::make('notes')
                                ->helperText('These notes will help when generating the small companion article designed to entice readers to click.'),
                        ])
                        ->modalHeading('Generate Post')
                        ->modalSubmitActionLabel('Generate')
                        ->icon('heroicon-o-arrow-path'),

                    Action::make('Put back in pending')
                        ->action(fn (Link $record) => $record->update([
                            'is_approved' => null,
                            'is_declined' => null,
                        ]))
                        ->icon('heroicon-o-queue-list')
                        ->hidden(fn (Link $record) => is_null($record->is_approved) && is_null($record->is_declined)),

                    EditAction::make()
                        ->icon('heroicon-o-pencil'),

                    DeleteAction::make()
                        ->icon('heroicon-o-trash'),
                ]),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    BulkAction::make('approve')
                        ->action(fn (Collection $records) => $records->each->approve())
                        ->color('success')
                        ->icon('heroicon-o-check'),

                    BulkAction::make('decline')
                        ->action(fn (Collection $records) => $records->each->decline())
                        ->color('danger')
                        ->icon('heroicon-o-x-circle'),

                    BulkAction::make('Put back in pending')
                        ->action(fn (Collection $records) => $records->each->update([
                            'is_approved' => null,
                            'is_declined' => null,
                        ]))
                        ->icon('heroicon-o-queue-list'),

                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages() : array
    {
        return [
            'index' => ListLinks::route('/'),
            'create' => CreateLink::route('/create'),
            'edit' => EditLink::route('/{record}/edit'),
        ];
    }

    public static function getGloballySearchableAttributes() : array
    {
        return ['user.name', 'url', 'title', 'description'];
    }

    public static function getGlobalSearchResultDetails($record) : array
    {
        return [
            'From' => $record->user->name,
            'URL' => $record->url,
        ];
    }
}
