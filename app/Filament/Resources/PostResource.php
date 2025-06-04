<?php

namespace App\Filament\Resources;

use App\Str;
use Filament\Forms;
use App\Models\Post;
use Filament\Tables;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Spatie\Image\Image;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Spatie\Image\Enums\Fit;
use Filament\Resources\Resource;
use Filament\Support\Enums\FontWeight;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\PostResource\Pages;
use App\Filament\Forms\Components\MarkdownEditor;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use App\Filament\Resources\PostResource\RelationManagers\CategoriesRelationManager;

class PostResource extends Resource
{
    protected static ?string $model = Post::class;

    protected static ?string $navigationGroup = 'Blog';

    protected static ?string $navigationIcon = 'heroicon-o-newspaper';

    protected static ?int $navigationSort = 1;

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Form $form) : Form
    {
        return $form
            ->schema([
                Forms\Components\FileUpload::make('image_path')
                    ->image()
                    ->disk(fn (Post $record) : string => $record->image_disk ?? 'public')
                    ->directory('posts')
                    ->saveUploadedFileUsing(function (TemporaryUploadedFile $file) {
                        if (str_contains($file->getMimeType(), 'image/')) {
                            $image = Image::load($file->path());

                            if ($image->getWidth() > 1500 || $image->getHeight() > 1500) {
                                $image->fit(Fit::Contain, 1500, 1500);
                            }

                            $image
                                ->quality(70)
                                ->optimize()
                                ->save($file->path());
                        }

                        return $file->storePubliclyAs('posts', Str::ulid() . '.' . $file->getClientOriginalExtension(), ['disk' => 'public']);
                    })
                    ->columnSpanFull()
                    ->label('Image'),

                Forms\Components\Select::make('image_disk')
                    ->options(['public' => 'Public'])
                    ->default('public')
                    ->columnSpanFull()
                    ->label('Image Disk'),

                Forms\Components\TextInput::make('title')
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

                Forms\Components\TextInput::make('slug')
                    ->required()
                    ->maxLength(255),

                MarkdownEditor::make('content')
                    ->required()
                    ->fileAttachmentsDisk('public')
                    ->fileAttachmentsDirectory('posts')
                    ->columnSpanFull(),

                Forms\Components\Textarea::make('description')
                    ->maxLength(65535)
                    ->columnSpanFull(),

                Forms\Components\TextInput::make('canonical_url')
                    ->nullable()
                    ->maxLength(255)
                    ->rules('url')
                    ->columnSpanFull(),

                Forms\Components\Select::make('user_id')
                    ->relationship('user', 'name')
                    ->required()
                    ->label('Author'),

                Forms\Components\DateTimePicker::make('published_at')
                    ->timezone('Europe/Paris')
                    ->native(false),

                Forms\Components\DateTimePicker::make('modified_at')
                    ->timezone('Europe/Paris')
                    ->native(false),
            ]);
    }

    public static function table(Table $table) : Table
    {
        return $table
            ->defaultSort('id', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->sortable()
                    ->label('ID')
                    ->weight(FontWeight::Bold),

                Tables\Columns\ImageColumn::make('image_path')
                    ->disk(fn (Post $record) => $record->image_disk ?? config('filesystems.default'))
                    ->defaultImageUrl(secure_asset('img/placeholder.svg'))
                    ->width(107)
                    ->height(80)
                    ->label('Image'),

                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->limit(50),

                Tables\Columns\TextColumn::make('user.name')
                    ->searchable()
                    ->label('Author'),

                Tables\Columns\TextColumn::make('canonical_url')
                    ->default('-')
                    ->label('Canonical URL')
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('categories')
                    ->getStateUsing(fn (Post $record) => $record->categories->pluck('name')->join(','))
                    ->badge()
                    ->separator(','),

                Tables\Columns\TextColumn::make('published_at')
                    ->date()
                    ->sortable()
                    ->label('Publication Date'),

                Tables\Columns\TextColumn::make('modified_at')
                    ->date()
                    ->sortable()
                    ->label('Modification Date')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('published_at')
                    ->nullable()
                    ->label('Published Status')
                    ->placeholder('Both')
                    ->trueLabel('Published')
                    ->falseLabel('Draft')
                    ->queries(
                        blank: fn (Builder $query) => $query,
                        true: fn (Builder $query) => $query->whereNotNull('published_at'),
                        false: fn (Builder $query) => $query->whereNull('published_at'),
                    ),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->icon('')
                    ->button()
                    ->outlined()
                    ->size('xs'),

                Tables\Actions\DeleteAction::make()
                    ->icon('')
                    ->button()
                    ->outlined()
                    ->size('xs'),

                Tables\Actions\ActionGroup::make([
                    Tables\Actions\Action::make('activities')
                        ->url(fn (Model $record) => self::getUrl('activities', compact('record')))
                        ->icon('heroicon-o-list-bullet'),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
                Tables\Actions\ForceDeleteBulkAction::make(),
                Tables\Actions\RestoreBulkAction::make(),
            ]);
    }

    public static function getPages() : array
    {
        return [
            'index' => Pages\ListPosts::route('/'),
            'activities' => Pages\ListPostActivities::route('/{record}/activities'),
            'create' => Pages\CreatePost::route('/create'),
            'edit' => Pages\EditPost::route('/{record}/edit'),
        ];
    }

    public static function getRelations() : array
    {
        return [
            CategoriesRelationManager::class,
        ];
    }

    public static function getGloballySearchableAttributes() : array
    {
        return ['user.name', 'title', 'slug', 'content', 'description', 'canonical_url'];
    }

    public static function getGlobalSearchResultDetails(Model $record) : array
    {
        return ['Author' => $record->user->name];
    }
}
