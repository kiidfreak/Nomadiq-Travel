<?php

namespace App\Filament\Resources\BookingResource\Pages;

use App\Filament\Resources\BookingResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBookings extends ListRecords
{
    protected static string $resource = BookingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            Actions\Action::make('refresh')
                ->label('Refresh')
                ->icon('heroicon-o-arrow-path')
                ->color('gray')
                ->action(fn () => $this->refreshTable())
                ->tooltip('Refresh the bookings list'),
        ];
    }
}
