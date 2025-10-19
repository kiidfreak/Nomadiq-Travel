<?php

namespace App\Filament\Resources\CustomItineraryResource\Pages;

use App\Filament\Resources\CustomItineraryResource;
use App\Models\CustomItinerary;
use Filament\Actions;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\Pages\EditRecord;

class EditCustomItinerary extends EditRecord
{
    protected static string $resource = CustomItineraryResource::class;

    protected function getHeaderActions(): array
    {
        return [
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

    public function form(Form $form): Form
    {
        // Get all days for this reference ID
        $allDays = CustomItinerary::where('reference_id', $this->record->reference_id)
            ->orderBy('day_number')
            ->get();

        $tabs = [];

        // Customer Overview Tab
        $tabs[] = Tabs\Tab::make('Customer Overview')
            ->schema([
                Card::make()
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('customer_name')
                                    ->label('Customer Name')
                                    ->disabled(),
                                TextInput::make('customer_email')
                                    ->label('Customer Email')
                                    ->disabled(),
                                TextInput::make('customer_phone')
                                    ->label('Customer Phone')
                                    ->disabled(),
                                TextInput::make('reference_id')
                                    ->label('Reference ID')
                                    ->disabled(),
                                Select::make('package_id')
                                    ->label('Package')
                                    ->relationship('package', 'title')
                                    ->disabled(),
                                Select::make('status')
                                    ->label('Status')
                                    ->options([
                                        'pending_approval' => 'Pending Approval',
                                        'approved' => 'Approved',
                                        'rejected' => 'Rejected',
                                        'needs_revision' => 'Needs Revision',
                                    ]),
                            ]),
                        Textarea::make('special_requests')
                            ->label('Special Requests')
                            ->rows(3)
                            ->columnSpanFull(),
                        Textarea::make('admin_notes')
                            ->label('Admin Notes')
                            ->placeholder('Internal notes for admin use only...')
                            ->rows(3)
                            ->columnSpanFull(),
                    ])
                    ->columnSpanFull(),
            ]);

        // Day-specific tabs
        foreach ($allDays as $day) {
            $tabs[] = Tabs\Tab::make("Day {$day->day_number}")
                ->schema([
                    Card::make()
                        ->schema([
                            TextInput::make("day_{$day->day_number}_title")
                                ->label('Day Title')
                                ->columnSpanFull(),
                            Textarea::make("day_{$day->day_number}_description")
                                ->label('Day Description')
                                ->rows(2)
                                ->columnSpanFull(),
                            Grid::make(2)
                                ->schema([
                                    TextInput::make("day_{$day->day_number}_accommodation_preference")
                                        ->label('Accommodation Preference'),
                                    TextInput::make("day_{$day->day_number}_meals_preference")
                                        ->label('Meal Preferences'),
                                ]),
                            Textarea::make("day_{$day->day_number}_activities_preference")
                                ->label('Activity Preferences')
                                ->rows(3)
                                ->columnSpanFull(),
                            Textarea::make("day_{$day->day_number}_special_notes")
                                ->label('Special Notes')
                                ->rows(2)
                                ->columnSpanFull(),
                        ])
                        ->columnSpanFull(),
                ]);
        }

        return $form
            ->schema([
                Tabs::make('Itinerary Details')
                    ->tabs($tabs)
                    ->columnSpanFull()
                    ->activeTab(1),
            ]);
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        // Get all days for this reference ID
        $allDays = CustomItinerary::where('reference_id', $this->record->reference_id)
            ->orderBy('day_number')
            ->get();

        // Populate form data with day-specific information
        foreach ($allDays as $day) {
            $dayNumber = $day->day_number;
            
            // Add day-specific data to the form data array
            $data["day_{$dayNumber}_title"] = $day->title;
            $data["day_{$dayNumber}_description"] = $day->description;
            $data["day_{$dayNumber}_accommodation_preference"] = $day->accommodation_preference;
            $data["day_{$dayNumber}_meals_preference"] = $day->meals_preference;
            $data["day_{$dayNumber}_activities_preference"] = $day->activities_preference;
            $data["day_{$dayNumber}_special_notes"] = $day->special_notes;
        }

        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        // Get all days for this reference ID
        $allDays = CustomItinerary::where('reference_id', $this->record->reference_id)
            ->orderBy('day_number')
            ->get();

        // Update each day's data
        foreach ($allDays as $day) {
            $dayNumber = $day->day_number;
            
            // Update day-specific fields if they exist in the form data
            if (isset($data["day_{$dayNumber}_title"])) {
                $day->title = $data["day_{$dayNumber}_title"];
            }
            if (isset($data["day_{$dayNumber}_description"])) {
                $day->description = $data["day_{$dayNumber}_description"];
            }
            if (isset($data["day_{$dayNumber}_accommodation_preference"])) {
                $day->accommodation_preference = $data["day_{$dayNumber}_accommodation_preference"];
            }
            if (isset($data["day_{$dayNumber}_meals_preference"])) {
                $day->meals_preference = $data["day_{$dayNumber}_meals_preference"];
            }
            if (isset($data["day_{$dayNumber}_activities_preference"])) {
                $day->activities_preference = $data["day_{$dayNumber}_activities_preference"];
            }
            if (isset($data["day_{$dayNumber}_special_notes"])) {
                $day->special_notes = $data["day_{$dayNumber}_special_notes"];
            }
            
            // Update common fields for all days
            $day->status = $data['status'] ?? $day->status;
            $day->special_requests = $data['special_requests'] ?? $day->special_requests;
            $day->admin_notes = $data['admin_notes'] ?? $day->admin_notes;
            
            $day->save();
        }

        // Return only the fields that belong to the main record
        return [
            'status' => $data['status'] ?? $this->record->status,
            'special_requests' => $data['special_requests'] ?? $this->record->special_requests,
            'admin_notes' => $data['admin_notes'] ?? $this->record->admin_notes,
        ];
    }
}
