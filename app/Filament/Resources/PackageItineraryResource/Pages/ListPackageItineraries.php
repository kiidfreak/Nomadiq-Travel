<?php

namespace App\Filament\Resources\PackageItineraryResource\Pages;

use App\Filament\Resources\PackageItineraryResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPackageItineraries extends ListRecords
{
    protected static string $resource = PackageItineraryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Add Itinerary Day')
                ->icon('heroicon-o-plus'),
        ];
    }

    public function getTitle(): string
    {
        return 'Package Itineraries';
    }

    public function getSubheading(): string
    {
        return 'Manage day-by-day package itineraries';
    }
}
