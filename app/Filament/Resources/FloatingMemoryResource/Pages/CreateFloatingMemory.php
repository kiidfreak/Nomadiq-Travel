<?php

namespace App\Filament\Resources\FloatingMemoryResource\Pages;

use App\Filament\Resources\FloatingMemoryResource;
use Filament\Resources\Pages\CreateRecord;

class CreateFloatingMemory extends CreateRecord
{
    protected static string $resource = FloatingMemoryResource::class;

    public function getTitle(): string
    {
        return 'Add Safari Memory';
    }

    public function getSubheading(): string
    {
        return 'Upload and share a beautiful moment from the safari';
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getCreatedNotificationTitle(): ?string
    {
        return 'Safari memory added successfully!';
    }
}
