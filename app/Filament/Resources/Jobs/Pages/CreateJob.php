<?php

namespace App\Filament\Resources\Jobs\Pages;

use App\Jobs\ScrapeJob;
use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\Jobs\JobResource;

/**
 * Defines the CreateJob implementation.
 */
class CreateJob extends CreateRecord
{
    protected static string $resource = JobResource::class;

    public function form(Schema $schema) : Schema
    {
        return $schema->components([
            TextInput::make('url')
                ->required()
                ->rules(['url'])
                ->label('URL')
                ->columnSpanFull(),
        ]);
    }

    protected function getSubmitFormLivewireMethodName() : string
    {
        return 'fetch';
    }

    public function fetch() : void
    {
        $this->validate();

        $data = $this->form->getState();

        ScrapeJob::dispatch($data['url']);

        Notification::make()
            ->title('Fetching the job')
            ->body('The job is being fetched in the background. This may take a while.')
            ->success()
            ->send();

        $this->redirect($this->getResourceUrl());
    }
}
