<?php

namespace App\Filament\Resources\InquiryResource\Pages;

use App\Filament\Resources\InquiryResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditInquiry extends EditRecord
{
    protected static string $resource = InquiryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\Action::make('mark_responded')
                ->label('Mark as Responded')
                ->icon('heroicon-o-check-circle')
                ->color('info')
                ->action(fn () => $this->getRecord()->markAsResponded())
                ->visible(fn () => !$this->getRecord()->isResponded() && !$this->getRecord()->isClosed())
                ->requiresConfirmation(),
            Actions\Action::make('mark_closed')
                ->label('Mark as Closed')
                ->icon('heroicon-o-x-circle')
                ->color('success')
                ->action(fn () => $this->getRecord()->markAsClosed())
                ->visible(fn () => !$this->getRecord()->isClosed())
                ->requiresConfirmation(),
            Actions\DeleteAction::make(),
        ];
    }

    public function getTitle(): string
    {
        return 'Edit: ' . ($this->getRecord()->inquiry_title ?? 'Customer Inquiry');
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getSavedNotificationTitle(): ?string
    {
        return 'Inquiry updated successfully!';
    }
}
