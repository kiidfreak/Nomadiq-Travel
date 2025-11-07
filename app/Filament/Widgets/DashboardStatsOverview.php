<?php

namespace App\Filament\Widgets;

use App\Models\Package;
use App\Models\Booking;
use App\Models\Customer;
use App\Models\Inquiry;
use App\Models\Testimonial;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class DashboardStatsOverview extends BaseWidget
{
    protected static ?int $sort = 2;
    
    protected function getStats(): array
    {
        return [
            Stat::make('Total Packages', Package::where('is_active', true)->count())
                ->description('Active travel packages')
                ->descriptionIcon('heroicon-m-rectangle-stack')
                ->color('primary'),
            
            Stat::make('Total Bookings', Booking::count())
                ->description('All bookings')
                ->descriptionIcon('heroicon-m-calendar-days')
                ->color('success'),
            
            Stat::make('Customers', Customer::count())
                ->description('Registered customers')
                ->descriptionIcon('heroicon-m-users')
                ->color('info'),
            
            Stat::make('Pending Inquiries', Inquiry::where('status', 'pending')->count())
                ->description('Awaiting response')
                ->descriptionIcon('heroicon-m-envelope')
                ->color('warning'),
            
            Stat::make('Testimonials', Testimonial::where('is_published', true)->count())
                ->description('Published reviews')
                ->descriptionIcon('heroicon-m-star')
                ->color('success'),
        ];
    }
}

