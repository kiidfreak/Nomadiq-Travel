<?php

namespace App\Filament\Resources\CustomItineraryResource\Pages;

use App\Filament\Resources\CustomItineraryResource;
use App\Models\CustomItinerary;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Filament\Support\Colors\Color;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\Tabs;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\Grid;
use Illuminate\Support\Facades\Mail;

class ViewCustomItinerary extends ViewRecord
{
    protected static string $resource = CustomItineraryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
            
            Actions\Action::make('approve')
                ->label('Approve Itinerary')
                ->icon('heroicon-o-check-circle')
                ->color('success')
                ->requiresConfirmation()
                ->modalHeading('Approve Custom Itinerary')
                ->modalDescription('Are you sure you want to approve this entire custom itinerary? The customer will be notified via email.')
                ->action(function () {
                    // Update all days for this reference ID
                    CustomItinerary::where('reference_id', $this->record->reference_id)
                        ->update([
                            'status' => 'approved',
                            'reviewed_at' => now(),
                            'reviewed_by' => auth()->id(),
                        ]);
                    
                    // Send approval email
                    try {
                        Mail::send('emails.custom-itinerary-approved', [
                            'customer_name' => $this->record->customer_name,
                            'reference_id' => $this->record->reference_id,
                            'package_name' => $this->record->package->title,
                        ], function ($message) {
                            $message->to($this->record->customer_email, $this->record->customer_name)
                                    ->subject('Custom Itinerary Approved - ' . $this->record->reference_id)
                                    ->from(config('mail.from.address'), config('mail.from.name'));
                        });
                    } catch (\Exception $e) {
                        \Log::error('Failed to send approval email: ' . $e->getMessage());
                    }
                    
                    $this->refreshFormData(['status']);
                })
                ->visible(fn (): bool => $this->record->status !== 'approved'),
                
            Actions\Action::make('needs_revision')
                ->label('Needs Revision')
                ->icon('heroicon-o-chat-bubble-bottom-center-text')
                ->color('info')
                ->requiresConfirmation()
                ->modalHeading('Mark as Needs Revision')
                ->modalDescription('Mark this custom itinerary as needing revision. The customer will be contacted for modifications.')
                ->action(function () {
                    // Update all days for this reference ID
                    CustomItinerary::where('reference_id', $this->record->reference_id)
                        ->update([
                            'status' => 'needs_revision',
                            'reviewed_at' => now(),
                            'reviewed_by' => auth()->id(),
                        ]);
                    
                    // Send revision email
                    try {
                        Mail::send('emails.custom-itinerary-rejected', [
                            'customer_name' => $this->record->customer_name,
                            'reference_id' => $this->record->reference_id,
                            'package_name' => $this->record->package->title,
                        ], function ($message) {
                            $message->to($this->record->customer_email, $this->record->customer_name)
                                    ->subject('Custom Itinerary Update - ' . $this->record->reference_id)
                                    ->from(config('mail.from.address'), config('mail.from.name'));
                        });
                    } catch (\Exception $e) {
                        \Log::error('Failed to send revision email: ' . $e->getMessage());
                    }
                    
                    $this->refreshFormData(['status']);
                })
                ->visible(fn (): bool => !in_array($this->record->status, ['approved', 'needs_revision'])),
                
            Actions\Action::make('reject')
                ->label('Reject')
                ->icon('heroicon-o-x-circle')
                ->color('danger')
                ->requiresConfirmation()
                ->modalHeading('Reject Custom Itinerary')
                ->modalDescription('Are you sure you want to reject this custom itinerary? This action cannot be undone and the customer will be notified.')
                ->action(function () {
                    // Update all days for this reference ID
                    CustomItinerary::where('reference_id', $this->record->reference_id)
                        ->update([
                            'status' => 'rejected',
                            'reviewed_at' => now(),
                            'reviewed_by' => auth()->id(),
                        ]);
                    
                    // Send rejection email
                    try {
                        Mail::send('emails.custom-itinerary-rejected', [
                            'customer_name' => $this->record->customer_name,
                            'reference_id' => $this->record->reference_id,
                            'package_name' => $this->record->package->title,
                        ], function ($message) {
                            $message->to($this->record->customer_email, $this->record->customer_name)
                                    ->subject('Custom Itinerary Rejected - ' . $this->record->reference_id)
                                    ->from(config('mail.from.address'), config('mail.from.name'));
                        });
                    } catch (\Exception $e) {
                        \Log::error('Failed to send rejection email: ' . $e->getMessage());
                    }
                    
                    $this->refreshFormData(['status']);
                })
                ->visible(fn (): bool => $this->record->status === 'pending_approval'),
                
            Actions\DeleteAction::make()
                ->label('Delete Submission')
                ->requiresConfirmation()
                ->modalHeading('Delete Custom Itinerary Submission')
                ->modalDescription('Are you sure you want to delete this entire custom itinerary submission? This will remove all days and cannot be undone.')
                ->action(function () {
                    // Delete all days for this reference ID
                    CustomItinerary::where('reference_id', $this->record->reference_id)->delete();
                    
                    // Redirect back to list page
                    return redirect()->route('filament.admin.resources.custom-itineraries.index');
                })
                ->color('danger'),
        ];
    }

    public function infolist(Infolist $infolist): Infolist
    {
        $allDays = CustomItinerary::where('reference_id', $this->record->reference_id)
            ->orderBy('day_number')
            ->get();

        return $infolist
            ->schema([
                // Customer & Package Overview Section
                Section::make('Submission Overview')
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                TextEntry::make('reference_id')
                                    ->label('Reference ID')
                                    ->badge()
                                    ->color(Color::Blue),
                                TextEntry::make('status')
                                    ->label('Status')
                                    ->badge()
                                    ->color(fn (string $state): string => match ($state) {
                                        'pending_approval' => 'warning',
                                        'approved' => 'success',
                                        'rejected' => 'danger',
                                        'needs_revision' => 'info',
                                        default => 'gray',
                                    })
                                    ->formatStateUsing(fn (string $state): string => match ($state) {
                                        'pending_approval' => 'Pending Approval',
                                        'approved' => 'Approved',
                                        'rejected' => 'Rejected', 
                                        'needs_revision' => 'Needs Revision',
                                        default => $state,
                                    }),
                                TextEntry::make('submitted_at')
                                    ->label('Submitted')
                                    ->dateTime('M j, Y \a\t g:i A'),
                            ]),
                        
                        Grid::make(3)
                            ->schema([
                                TextEntry::make('customer_name')
                                    ->label('Customer Name')
                                    ->icon('heroicon-o-user'),
                                TextEntry::make('customer_email')
                                    ->label('Email')
                                    ->icon('heroicon-o-envelope')
                                    ->copyable(),
                                TextEntry::make('customer_phone')
                                    ->label('Phone')
                                    ->icon('heroicon-o-phone')
                                    ->placeholder('Not provided'),
                            ]),
                            
                        Grid::make(2)
                            ->schema([
                                TextEntry::make('package.title')
                                    ->label('Package')
                                    ->icon('heroicon-o-map'),
                                TextEntry::make('total_days')
                                    ->label('Total Days')
                                    ->getStateUsing(fn () => $allDays->count())
                                    ->badge()
                                    ->icon('heroicon-o-calendar-days'),
                            ]),
                            
                        TextEntry::make('special_requests')
                            ->label('Special Requests')
                            ->placeholder('No special requests')
                            ->columnSpanFull(),
                    ])
                    ->columns(1),

                // Day-by-Day Itinerary Tabs
                Tabs::make('Itinerary Days')
                    ->tabs($allDays->map(function ($day) {
                        return Tabs\Tab::make("Day {$day->day_number}")
                            ->badge($day->day_number)
                            ->badgeColor(Color::Gray)
                            ->schema([
                                Section::make("Day {$day->day_number}: {$day->title}")
                                    ->schema([
                                        TextEntry::make('title')
                                            ->label('Day Title')
                                            ->getStateUsing(fn () => $day->title)
                                            ->icon('heroicon-o-map-pin'),
                                            
                                        TextEntry::make('description')
                                            ->label('Description')
                                            ->getStateUsing(fn () => $day->description)
                                            ->columnSpanFull(),
                                            
                                        Grid::make(2)
                                            ->schema([
                                                TextEntry::make('accommodation_preference')
                                                    ->label('Accommodation Preference')
                                                    ->getStateUsing(fn () => $day->accommodation_preference ?: 'No preference specified')
                                                    ->icon('heroicon-o-building-office'),
                                                TextEntry::make('meals_preference')
                                                    ->label('Meals Preference')
                                                    ->getStateUsing(fn () => $day->meals_preference ?: 'No preference specified')
                                                    ->icon('heroicon-o-cake'),
                                            ]),
                                            
                                        TextEntry::make('activities_preference')
                                            ->label('Activities Preference')
                                            ->getStateUsing(fn () => $day->activities_preference ?: 'No specific activities mentioned')
                                            ->icon('heroicon-o-camera')
                                            ->columnSpanFull(),
                                            
                                        TextEntry::make('special_notes')
                                            ->label('Special Notes for This Day')
                                            ->getStateUsing(fn () => $day->special_notes ?: 'No special notes')
                                            ->icon('heroicon-o-chat-bubble-bottom-center-text')
                                            ->columnSpanFull(),
                                    ])
                                    ->columns(2),
                            ]);
                    })->toArray())
                    ->columnSpanFull(),
                    
                // Admin Section
                Section::make('Admin Information')
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                TextEntry::make('reviewed_at')
                                    ->label('Reviewed At')
                                    ->dateTime('M j, Y \a\t g:i A')
                                    ->placeholder('Not reviewed yet'),
                                TextEntry::make('reviewer.name')
                                    ->label('Reviewed By')
                                    ->placeholder('Not reviewed yet'),
                                TextEntry::make('admin_notes')
                                    ->label('Admin Notes')
                                    ->placeholder('No admin notes'),
                            ]),
                    ])
                    ->collapsible()
                    ->collapsed()
                    ->columnSpanFull(),
            ]);
    }
}
