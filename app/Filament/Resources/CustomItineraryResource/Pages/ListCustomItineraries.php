<?php

namespace App\Filament\Resources\CustomItineraryResource\Pages;

use App\Filament\Resources\CustomItineraryResource;
use App\Models\CustomItinerary;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Components\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListCustomItineraries extends ListRecords
{
    protected static string $resource = CustomItineraryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
    
    public function getTabs(): array
    {
        return [
            'all' => Tab::make('All Submissions'),
            'pending' => Tab::make('Pending Approval')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'pending_approval'))
                ->badge(fn () => CustomItinerary::where('status', 'pending_approval')
                    ->select('reference_id')
                    ->distinct()
                    ->count())
                ->badgeColor('warning'),
            'approved' => Tab::make('Approved')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'approved'))
                ->badge(fn () => CustomItinerary::where('status', 'approved')
                    ->select('reference_id')
                    ->distinct()
                    ->count())
                ->badgeColor('success'),
            'needs_revision' => Tab::make('Needs Revision')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'needs_revision'))
                ->badge(fn () => CustomItinerary::where('status', 'needs_revision')
                    ->select('reference_id')
                    ->distinct()
                    ->count())
                ->badgeColor('info'),
            'rejected' => Tab::make('Rejected')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'rejected'))
                ->badge(fn () => CustomItinerary::where('status', 'rejected')
                    ->select('reference_id')
                    ->distinct()
                    ->count())
                ->badgeColor('danger'),
        ];
    }
    
    public function getDefaultActiveTab(): string | int | null
    {
        return 'pending';
    }
}
