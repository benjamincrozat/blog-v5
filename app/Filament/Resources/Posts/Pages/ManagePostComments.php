<?php

namespace App\Filament\Resources\Posts\Pages;

use Filament\Tables\Table;
use Filament\Schemas\Schema;
use Illuminate\Contracts\Support\Htmlable;
use App\Filament\Resources\CommentResource;
use App\Filament\Resources\Posts\PostResource;
use Filament\Resources\Pages\ManageRelatedRecords;

/**
 * Defines the ManagePostComments implementation.
 */
class ManagePostComments extends ManageRelatedRecords
{
    protected static string $resource = PostResource::class;

    protected static string $relationship = 'comments';

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-chat-bubble-left-ellipsis';

    public function form(Schema $schema) : Schema
    {
        return CommentResource::form($schema);
    }

    public function table(Table $table) : Table
    {
        return CommentResource::table($table);
    }

    public function getTitle() : string|Htmlable
    {
        return "Manage \"{$this->getRecordTitle()}\" comments";
    }

    public function getBreadcrumb() : string
    {
        return 'Manage Comments';
    }

    public static function getNavigationLabel() : string
    {
        return 'Manage Comments';
    }
}
