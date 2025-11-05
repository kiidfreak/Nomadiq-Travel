<?php

namespace App\Filament\Resources\ProposalResource\Pages;

use App\Filament\Resources\ProposalResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Components\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListProposals extends ListRecords
{
    protected static string $resource = ProposalResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            ProposalResource\Widgets\ProposalStatsOverview::class,
        ];
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make('All Proposals')
                ->badge(fn () => \App\Models\Proposal::count()),
            
            'new' => Tab::make('New')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'new'))
                ->badge(fn () => \App\Models\Proposal::where('status', 'new')->count())
                ->badgeColor('danger'),
            
            'unviewed' => Tab::make('Unviewed')
                ->modifyQueryUsing(fn (Builder $query) => $query->whereNull('viewed_at'))
                ->badge(fn () => \App\Models\Proposal::whereNull('viewed_at')->count())
                ->badgeColor('warning'),
            
            'reviewing' => Tab::make('Reviewing')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'reviewing'))
                ->badge(fn () => \App\Models\Proposal::where('status', 'reviewing')->count()),
            
            'quoted' => Tab::make('Quoted')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'quoted'))
                ->badge(fn () => \App\Models\Proposal::where('status', 'quoted')->count())
                ->badgeColor('info'),
            
            'accepted' => Tab::make('Accepted')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'accepted'))
                ->badge(fn () => \App\Models\Proposal::where('status', 'accepted')->count())
                ->badgeColor('success'),
            
            'my_proposals' => Tab::make('My Proposals')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('assigned_to', auth()->id()))
                ->badge(fn () => \App\Models\Proposal::where('assigned_to', auth()->id())->count()),
            
            'urgent' => Tab::make('Urgent')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('priority', 'urgent')
                    ->orWhere(function ($q) {
                        $q->where('priority', 'high')
                            ->where('created_at', '<=', now()->subDays(2));
                    }))
                ->badge(fn () => \App\Models\Proposal::where('priority', 'urgent')->count())
                ->badgeColor('danger'),
        ];
    }
}