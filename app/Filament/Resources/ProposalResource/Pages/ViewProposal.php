<?php

namespace App\Filament\Resources\ProposalResource\Pages;

use App\Filament\Resources\ProposalResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Filament\Notifications\Notification;

class ViewProposal extends ViewRecord
{
    protected static string $resource = ProposalResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
            
            Actions\Action::make('assignToMe')
                ->label('Assign to Me')
                ->icon('heroicon-o-user-plus')
                ->color('info')
                ->action(function () {
                    $this->record->assignTo(auth()->id());
                    Notification::make()
                        ->success()
                        ->title('Assigned to you')
                        ->send();
                })
                ->visible(fn () => $this->record->assigned_to !== auth()->id()),
            
            Actions\Action::make('sendEmail')
                ->label('Email Client')
                ->icon('heroicon-o-envelope')
                ->color('warning')
                ->url(fn () => "mailto:{$this->record->email}?subject=Safari Proposal #{$this->record->id}")
                ->openUrlInNewTab(),
            
            Actions\Action::make('viewEstimate')
                ->label('View Budget Estimate')
                ->icon('heroicon-o-calculator')
                ->color('success')
                ->modalContent(function () {
                    $estimate = $this->record->calculateEstimatedBudget();
                    
                    if (!$estimate) {
                        return view('filament.modals.no-estimate');
                    }
                    
                    return view('filament.modals.budget-estimate', [
                        'estimate' => $estimate,
                        'record' => $this->record,
                    ]);
                })
                ->modalSubmitAction(false)
                ->modalCancelActionLabel('Close'),
            
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        // Mark as viewed when opening the view page
        $this->record->markAsViewed();
        
        return $data;
    }
}