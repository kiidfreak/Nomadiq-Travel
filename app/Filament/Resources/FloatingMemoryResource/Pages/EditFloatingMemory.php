<?php

namespace App\Filament\Resources\FloatingMemoryResource\Pages;

use App\Filament\Resources\FloatingMemoryResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditFloatingMemory extends EditRecord
{
    protected static string $resource = FloatingMemoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }

    public function getTitle(): string
    {
        return 'Edit: ' . ($this->getRecord()->memory_title ?? 'Safari Memory');
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getSavedNotificationTitle(): ?string
    {
        return 'Memory updated successfully!';
    }
}
