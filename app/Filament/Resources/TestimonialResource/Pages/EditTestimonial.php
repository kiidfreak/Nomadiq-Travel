<?php

namespace App\Filament\Resources\TestimonialResource\Pages;

use App\Filament\Resources\TestimonialResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTestimonial extends EditRecord
{
    protected static string $resource = TestimonialResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\Action::make('toggle_published')
                ->label(fn () => $this->getRecord()->is_published ? 'Unpublish' : 'Publish')
                ->icon(fn () => $this->getRecord()->is_published ? 'heroicon-o-eye-slash' : 'heroicon-o-eye')
                ->color(fn () => $this->getRecord()->is_published ? 'warning' : 'success')
                ->action(fn () => $this->getRecord()->is_published ? $this->getRecord()->unpublish() : $this->getRecord()->publish())
                ->requiresConfirmation(),
            Actions\DeleteAction::make(),
        ];
    }

    public function getTitle(): string
    {
        return 'Edit: ' . ($this->getRecord()->testimonial_title ?? 'Testimonial');
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getSavedNotificationTitle(): ?string
    {
        return 'Testimonial updated successfully!';
    }
}
