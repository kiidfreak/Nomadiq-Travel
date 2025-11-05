<?php

namespace App\Filament\Resources\ProposalResource\Pages;

use App\Filament\Resources\ProposalResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Notifications\Notification;

class EditProposal extends EditRecord
{
    protected static string $resource = ProposalResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            
            Actions\Action::make('sendProposal')
                ->label('Send Proposal Email')
                ->icon('heroicon-o-paper-airplane')
                ->color('success')
                ->requiresConfirmation()
                ->modalHeading('Send Proposal to Client')
                ->modalDescription('This will mark the proposal as responded and send an email to the client.')
                ->action(function () {
                    $this->record->markAsResponded();
                    
                    // Here you would trigger your email sending logic
                    // Mail::to($this->record->email)->send(new ProposalMail($this->record));
                    
                    Notification::make()
                        ->success()
                        ->title('Proposal Sent')
                        ->body('The proposal has been sent to ' . $this->record->email)
                        ->send();
                })
                ->visible(fn () => filled($this->record->proposal_response)),
            
            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('view', ['record' => $this->record]);
    }

    protected function getSavedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('Proposal updated')
            ->body('The proposal has been saved successfully.');
    }
}