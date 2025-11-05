<?php

namespace App\Filament\Resources\ProposalResource\Widgets;

use App\Models\Proposal;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ProposalStatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $newCount = Proposal::where('status', 'new')->count();
        $reviewingCount = Proposal::where('status', 'reviewing')->count();
        $quotedCount = Proposal::where('status', 'quoted')->count();
        $acceptedCount = Proposal::where('status', 'accepted')->count();
        $unviewedCount = Proposal::whereNull('viewed_at')->count();
        $urgentCount = Proposal::where('priority', 'urgent')->count();
        
        $acceptanceRate = Proposal::whereIn('status', ['quoted', 'accepted', 'rejected'])->count();
        $acceptancePercentage = $acceptanceRate > 0 
            ? round(($acceptedCount / $acceptanceRate) * 100, 1) 
            : 0;

        return [
            Stat::make('New Proposals', $newCount)
                ->description('Awaiting review')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color($newCount > 5 ? 'danger' : 'success')
                ->chart([7, 4, 6, 8, 10, 12, $newCount]),
            
            Stat::make('Unviewed', $unviewedCount)
                ->description('Not yet opened')
                ->descriptionIcon('heroicon-m-eye-slash')
                ->color($unviewedCount > 0 ? 'warning' : 'success'),
            
            Stat::make('In Progress', $reviewingCount + $quotedCount)
                ->description('Being processed')
                ->descriptionIcon('heroicon-m-clock')
                ->color('info'),
            
            Stat::make('Accepted', $acceptedCount)
                ->description($acceptancePercentage . '% conversion rate')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success'),
            
            Stat::make('Urgent', $urgentCount)
                ->description('Requires immediate attention')
                ->descriptionIcon('heroicon-m-exclamation-triangle')
                ->color($urgentCount > 0 ? 'danger' : 'gray'),
            
            Stat::make('This Month', Proposal::whereMonth('created_at', now()->month)->count())
                ->description('Total proposals')
                ->descriptionIcon('heroicon-m-calendar')
                ->color('info'),
        ];
    }

    protected function getColumns(): int
    {
        return 3;
    }
}