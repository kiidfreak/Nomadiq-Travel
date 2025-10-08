<?php

namespace App\Filament\Resources\FloatingMemoryResource\Pages;

use App\Filament\Resources\FloatingMemoryResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewFloatingMemory extends ViewRecord
{
    protected static string $resource = FloatingMemoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
            Actions\DeleteAction::make(),
        ];
    }

    public function getTitle(): string
    {
        return $this->getRecord()->memory_title ?? 'Safari Memory';
    }
}
