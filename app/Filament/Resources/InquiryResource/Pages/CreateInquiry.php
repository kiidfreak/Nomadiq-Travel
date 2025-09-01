<?php

namespace App\Filament\Resources\InquiryResource\Pages;

use App\Filament\Resources\InquiryResource;
use Filament\Resources\Pages\CreateRecord;

class CreateInquiry extends CreateRecord
{
    protected static string $resource = InquiryResource::class;

    public function getTitle(): string
    {
        return 'Add New Inquiry';
    }

    public function getSubheading(): string
    {
        return 'Record a new customer inquiry or support request';
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getCreatedNotificationTitle(): ?string
    {
        return 'Inquiry created successfully!';
    }
}
