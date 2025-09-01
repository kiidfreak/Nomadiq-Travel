<?php

namespace App\Filament\Resources\PackageItineraryResource\Pages;

use App\Filament\Resources\PackageItineraryResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewPackageItinerary extends ViewRecord
{
    protected static string $resource = PackageItineraryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
