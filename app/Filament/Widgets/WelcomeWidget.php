<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;
use Illuminate\Support\Facades\Auth;

class WelcomeWidget extends Widget
{
    protected static string $view = 'filament.widgets.welcome-widget';
    
    protected int | string | array $columnSpan = 'full';
    
    protected static ?int $sort = 1;
    
    public function getGreeting(): string
    {
        $hour = (int) now()->format('H');
        
        if ($hour < 12) {
            return 'Good morning';
        } elseif ($hour < 17) {
            return 'Good afternoon';
        } else {
            return 'Good evening';
        }
    }
    
    public function getUserName(): string
    {
        return Auth::user()->name ?? 'there';
    }
}

