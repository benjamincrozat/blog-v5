<?php

namespace App\Filament\Resources\Subscribers;

use App\Models\Subscriber;
use Filament\Tables\Table;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Model;
use App\Filament\Resources\Subscribers\Pages\EditSubscriber;
use App\Filament\Resources\Subscribers\Pages\ListSubscribers;
use App\Filament\Resources\Subscribers\Pages\CreateSubscriber;
use App\Filament\Resources\Subscribers\Schemas\SubscriberForm;
use App\Filament\Resources\Subscribers\Tables\SubscribersTable;

class SubscriberResource extends Resource
{
    protected static ?string $model = Subscriber::class;

    protected static string|\UnitEnum|null $navigationGroup = 'Audience';

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $recordTitleAttribute = 'email';

    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema) : Schema
    {
        return SubscriberForm::configure($schema);
    }

    public static function table(Table $table) : Table
    {
        return SubscribersTable::configure($table);
    }

    public static function getPages() : array
    {
        return [
            'index' => ListSubscribers::route('/'),
            'create' => CreateSubscriber::route('/create'),
            'edit' => EditSubscriber::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge() : ?string
    {
        $pending = static::getModel()::query()
            ->whereNull('confirmed_at')
            ->count();

        return $pending > 0 ? number_format($pending) : null;
    }

    public static function getGloballySearchableAttributes() : array
    {
        return ['email'];
    }

    public static function getGlobalSearchResultDetails(Model $record) : array
    {
        return [
            'Status' => $record->needsConfirmation() ? 'Pending' : 'Confirmed',
        ];
    }
}
