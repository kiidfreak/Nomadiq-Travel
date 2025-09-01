<?php

namespace App\Filament\Resources\FloatingMemoryResource\Pages;

use App\Filament\Resources\FloatingMemoryResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListFloatingMemories extends ListRecords
{
    protected static string $resource = FloatingMemoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Add New Memory')
                ->icon('heroicon-o-plus'),
        ];
    }

    public function getTitle(): string
    {
        return 'Safari Memories';
    }

    public function getSubheading(): string
    {
        return 'Beautiful moments captured during our safari adventures';
    }
}
