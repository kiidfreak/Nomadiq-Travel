<?php

namespace App\Filament\Resources\MicroExperienceResource\Pages;

use App\Filament\Resources\MicroExperienceResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMicroExperience extends EditRecord
{
    protected static string $resource = MicroExperienceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
