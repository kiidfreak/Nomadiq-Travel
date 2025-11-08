<?php

namespace App\Filament\Resources\MicroExperienceResource\Pages;

use App\Filament\Resources\MicroExperienceResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMicroExperiences extends ListRecords
{
    protected static string $resource = MicroExperienceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
