<?php

namespace App\Filament\Resources\PackageItineraryResource\Pages;

use App\Filament\Resources\PackageItineraryResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPackageItinerary extends EditRecord
{
    protected static string $resource = PackageItineraryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }

    public function getTitle(): string
    {
        return 'Edit: Day ' . $this->getRecord()->day_number . ' Itinerary';
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getSavedNotificationTitle(): ?string
    {
        return 'Itinerary updated successfully!';
    }
}
