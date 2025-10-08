<?php

namespace App\Filament\Resources\PackageItineraryResource\Pages;

use App\Filament\Resources\PackageItineraryResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePackageItinerary extends CreateRecord
{
    protected static string $resource = PackageItineraryResource::class;

    public function getTitle(): string
    {
        return 'Add Package Itinerary';
    }

    public function getSubheading(): string
    {
        return 'Create a new day-by-day itinerary for this package';
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getCreatedNotificationTitle(): ?string
    {
        return 'Itinerary created successfully!';
    }
}
