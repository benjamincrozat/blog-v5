<?php

namespace App\Filament\Resources\Posts;

use App\Models\Post;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Filament\Schemas\Schema;
use Illuminate\Support\Number;
use Filament\Resources\Resource;
use Filament\Actions\ActionGroup;
use Filament\Resources\Pages\Page;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Date;
use Filament\Actions\BulkActionGroup;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Group;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\ImageColumn;
use Filament\Forms\Components\FileUpload;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Pages\Enums\SubNavigationPosition;
use App\Filament\Resources\Posts\Pages\EditPost;
use App\Filament\Resources\Posts\Pages\ListPosts;
use App\Filament\Resources\Posts\Pages\CreatePost;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\Posts\Actions\BulkActions;
use App\Filament\Resources\Categories\CategoryResource;
use App\Filament\Resources\Posts\Actions\RecordActions;
use App\Filament\Resources\Posts\Pages\ManagePostComments;

/**
 * Configures the post resource Filament resource.
 */
class PostResource extends Resource
{
    protected static ?string $model = Post::class;

    protected static string|\UnitEnum|null $navigationGroup = 'Blog';

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-newspaper';

    protected static ?int $navigationSort = 1;

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Schema $schema) : Schema
    {
        return $schema
            ->components([
                Group::make([
                    TextInput::make('title')
                        ->required()
                        ->maxLength(255)
                        ->live(onBlur: true)
                        ->afterStateUpdated(function (Get $get, Set $set, ?string $old, ?string $state) {
                            // Only update slug if it hasn't been manually customized.
                            if (($get('slug') ?? '') !== Str::slug($old)) {
                                return;
                            }

                            // If the slug hasn't been customized, update it to match the new title
                            $set('slug', Str::slug($state));
                        }),

                    MarkdownEditor::make('content')
                        ->fileAttachmentsDisk('cloudflare-images')
                        ->fileAttachmentsDirectory('images/posts')
                        ->required()
                        ->columnSpanFull(),
                ])
                    ->columnSpan([
                        'default' => 12,
                        'lg' => 8,
                    ]),

                Group::make([
                    FileUpload::make('image_path')
                        ->image()
                        ->disk(fn (Get $get, ?Post $record) => $get('image_disk') ?? $record?->image_disk ?? 'cloudflare-images')
                        ->directory('images/posts')
                        ->columnSpanFull()
                        ->label('Image')
                        ->helperText('Resizing and compression are applied automatically.')
                        ->afterStateUpdated(function (Get $get, Set $set, $state) {
                            if (blank($state)) {
                                return;
                            }

                            if (blank($get('image_disk'))) {
                                $set('image_disk', 'cloudflare-images');
                            }
                        }),

                    Select::make('image_disk')
                        ->label('Image Disk')
                        ->options(fn () => collect(config('filesystems.disks'))
                            ->mapWithKeys(fn (array $diskConfig, string $diskName) => [
                                $diskName => Str::headline($diskName),
                            ])
                            ->all())
                        ->default('cloudflare-images')
                        ->afterStateHydrated(function (Get $get, Set $set, $state) {
                            if (blank($state)) {
                                $set('image_disk', 'cloudflare-images');
                            }
                        })
                        ->live()
                        ->native(false)
                        ->searchable(),

                    TextInput::make('slug')
                        ->required()
                        ->maxLength(255)
                        ->helperText('An exact match with the main keyword is preferred.'),

                    Select::make('user_id')
                        ->relationship('user', 'name')
                        ->required()
                        ->default(fn () => Auth::id())
                        ->searchable()
                        ->label('Author'),

                    Select::make('categories')
                        ->relationship(name: 'categories', titleAttribute: 'name')
                        ->multiple()
                        ->searchable()
                        ->createOptionForm(CategoryResource::form($schema)->getComponents()),

                    TextInput::make('serp_title')
                        ->maxLength(255)
                        ->label('SERP Title')
                        ->helperText('This appears in the search results.'),

                    Textarea::make('description')
                        ->maxLength(65535)
                        ->columnSpanFull(),

                    Toggle::make('is_commercial')
                        ->label('Commercial')
                        ->default(false)
                        ->helperText('This gives readers a focused layout.')
                        ->columnSpanFull(),

                    TextInput::make('canonical_url')
                        ->nullable()
                        ->maxLength(255)
                        ->rules('url')
                        ->label('Canonical URL')
                        ->columnSpanFull(),

                    DateTimePicker::make('published_at')
                        ->timezone('Europe/Paris')
                        ->native(false)
                        ->seconds(false)
                        ->closeOnDateSelection()
                        ->placeholder(now())
                        ->defaultFocusedDate(now())
                        ->reactive()
                        ->afterStateUpdated(function (DateTimePicker $component, $state) {
                            if (blank($state)) {
                                return;
                            }

                            $now = now();

                            $date = Date::parse($state)->setTime($now->hour, $now->minute, $now->second);

                            $component->state($date);
                        })
                        ->label('Publication Date'),

                    DateTimePicker::make('modified_at')
                        ->timezone('Europe/Paris')
                        ->native(false)
                        ->seconds(false)
                        ->placeholder(now())
                        ->defaultFocusedDate(now())
                        ->closeOnDateSelection()
                        ->reactive()
                        ->afterStateUpdated(function (DateTimePicker $component, $state) {
                            if (blank($state)) {
                                return;
                            }

                            $now = now();

                            $date = Date::parse($state)->setTime($now->hour, $now->minute, $now->second);

                            $component->state($date);
                        })
                        ->label('Last Modification Date'),

                    DateTimePicker::make('sponsored_at')
                        ->timezone('Europe/Paris')
                        ->native(false)
                        ->placeholder(now())
                        ->defaultFocusedDate(now())
                        ->closeOnDateSelection()
                        ->label('Sponsored Date'),
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
                ImageColumn::make('image_path')
                    ->disk(fn (Post $record) => $record->image_disk ?? 'cloudflare-images')
                    ->imageWidth(107)
                    ->imageHeight(80)
                    ->default(secure_asset('img/placeholder.png'))
                    ->label('Image'),

                TextColumn::make('title')
                    ->searchable()
                    ->limit(50),

                TextColumn::make('user.name')
                    ->searchable()
                    ->label('Author'),

                TextColumn::make('canonical_url')
                    ->default('-')
                    ->label('Canonical URL')
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('categories')
                    ->getStateUsing(fn (Post $record) => $record->categories->pluck('name')->join(','))
                    ->badge()
                    ->separator(','),

                TextColumn::make('sessions_count')
                    ->formatStateUsing(fn (int $state) => Number::format($state))
                    ->sortable()
                    ->label('Sessions (7d)'),

                TextColumn::make('published_at')
                    ->date()
                    ->sortable()
                    ->label('Publication Date'),

                TextColumn::make('modified_at')
                    ->date()
                    ->sortable()
                    ->label('Modification Date')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters(Filters::configure())
            ->recordActions([
                ActionGroup::make(RecordActions::configure()),
            ])
            ->toolbarActions([
                BulkActionGroup::make(BulkActions::configure()),
            ]);
    }

    public static function getPages() : array
    {
        return [
            'index' => ListPosts::route('/'),
            'create' => CreatePost::route('/create'),
            'edit' => EditPost::route('/{record}/edit'),
            'comments' => ManagePostComments::route('/{record}/comments'),
        ];
    }

    public static function getGloballySearchableAttributes() : array
    {
        return ['user.name', 'title', 'serp_title', 'slug', 'content', 'description', 'canonical_url'];
    }

    public static function getGlobalSearchResultDetails(Model $record) : array
    {
        return ['Author' => $record->user->name];
    }

    public static function getRecordSubNavigation(Page $page) : array
    {
        return $page->generateNavigationItems([
            EditPost::class,
            ManagePostComments::class,
        ]);
    }

    public static function getSubNavigationPosition() : SubNavigationPosition
    {
        return SubNavigationPosition::Top;
    }

    public static function getRecordRouteBindingEloquentQuery() : Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
