<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProposalResource\Pages;
use App\Filament\Resources\ProposalResource\RelationManagers;
use App\Models\Proposal;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Notifications\Notification;
use Filament\Support\Colors\Color;

class ProposalResource extends Resource
{
    protected static ?string $model = Proposal::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationGroup = 'Sales';

    protected static ?int $navigationSort = 1;

    protected static ?string $recordTitleAttribute = 'full_name';

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('status', 'new')->count();
    }

    public static function getNavigationBadgeColor(): ?string
    {
        $count = static::getModel()::where('status', 'new')->count();
        return $count > 5 ? 'danger' : ($count > 0 ? 'warning' : 'success');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Tabs::make('Proposal Details')
                    ->tabs([
                        // Contact Information Tab
                        Forms\Components\Tabs\Tab::make('Contact Info')
                            ->icon('heroicon-o-user')
                            ->schema([
                                Forms\Components\Section::make('Contact Details')
                                    ->schema([
                                        Forms\Components\TextInput::make('full_name')
                                            ->required()
                                            ->maxLength(255),
                                        Forms\Components\TextInput::make('email')
                                            ->email()
                                            ->required()
                                            ->maxLength(255),
                                        Forms\Components\TextInput::make('phone')
                                            ->tel()
                                            ->required()
                                            ->maxLength(255),
                                        Forms\Components\TextInput::make('country')
                                            ->required()
                                            ->maxLength(255),
                                    ])->columns(2),
                            ]),

                        // Trip Details Tab
                        Forms\Components\Tabs\Tab::make('Trip Details')
                            ->icon('heroicon-o-map')
                            ->schema([
                                Forms\Components\Section::make('Destinations & Dates')
                                    ->schema([
                                        Forms\Components\CheckboxList::make('destinations')
                                            ->options([
                                                'kenya' => 'Kenya',
                                                'tanzania' => 'Tanzania',
                                                'uganda' => 'Uganda',
                                                'rwanda' => 'Rwanda',
                                                'south_africa' => 'South Africa',
                                                'botswana' => 'Botswana',
                                            ])
                                            ->columns(3)
                                            ->required(),
                                        Forms\Components\DatePicker::make('travel_start_date')
                                            ->native(false),
                                        Forms\Components\DatePicker::make('travel_end_date')
                                            ->native(false)
                                            ->after('travel_start_date'),
                                        Forms\Components\Select::make('duration')
                                            ->options([
                                                '3-5' => '3-5 days',
                                                '6-8' => '6-8 days',
                                                '9-12' => '9-12 days',
                                                '13-15' => '13-15 days',
                                                '16+' => '16+ days',
                                            ]),
                                    ])->columns(2),

                                Forms\Components\Section::make('Group Size')
                                    ->schema([
                                        Forms\Components\TextInput::make('adults')
                                            ->numeric()
                                            ->default(2)
                                            ->minValue(1)
                                            ->required(),
                                        Forms\Components\TextInput::make('children')
                                            ->numeric()
                                            ->default(0)
                                            ->minValue(0),
                                        Forms\Components\TextInput::make('children_ages')
                                            ->maxLength(255)
                                            ->visible(fn ($get) => $get('children') > 0),
                                    ])->columns(3),
                            ]),

                        // Accommodation Tab
                        Forms\Components\Tabs\Tab::make('Accommodation')
                            ->icon('heroicon-o-home')
                            ->schema([
                                Forms\Components\CheckboxList::make('accommodation_types')
                                    ->options([
                                        'luxury_lodge' => 'Luxury Safari Lodge',
                                        'luxury_tented' => 'Luxury Tented Camp',
                                        'boutique_camp' => 'Boutique Tented Camp',
                                        'mobile_camp' => 'Mobile Tented Camp',
                                        'mid_range' => 'Mid-Range Lodge',
                                        'budget_camp' => 'Budget Camping',
                                    ])
                                    ->descriptions([
                                        'luxury_lodge' => 'Permanent structures, all amenities',
                                        'luxury_tented' => 'Canvas walls, en-suite bathrooms',
                                        'boutique_camp' => 'Intimate, exclusive experience',
                                        'mobile_camp' => 'Seasonal, follows wildlife',
                                        'mid_range' => 'Comfortable, good value',
                                        'budget_camp' => 'Basic facilities, adventurous',
                                    ])
                                    ->columns(2),
                                Forms\Components\Select::make('room_configuration')
                                    ->options([
                                        'single' => 'Single Rooms',
                                        'double' => 'Double/Twin Rooms',
                                        'triple' => 'Triple Rooms',
                                        'family' => 'Family Rooms/Suites',
                                        'mix' => 'Mix of configurations',
                                    ]),
                            ]),

                        // Activities & Interests Tab
                        Forms\Components\Tabs\Tab::make('Activities')
                            ->icon('heroicon-o-camera')
                            ->schema([
                                Forms\Components\CheckboxList::make('activities')
                                    ->options([
                                        'game_drives' => 'Game Drives',
                                        'hot_air_balloon' => 'Hot Air Balloon Safari',
                                        'walking_safari' => 'Walking Safaris',
                                        'gorilla_trekking' => 'Gorilla Trekking',
                                        'boat_safari' => 'Boat Safaris',
                                        'cultural_visits' => 'Cultural Village Visits',
                                        'bird_watching' => 'Bird Watching',
                                        'photography' => 'Photography Safaris',
                                        'beach_extension' => 'Beach Extension',
                                    ])
                                    ->columns(3),
                                
                                Forms\Components\CheckboxList::make('special_interests')
                                    ->options([
                                        'honeymoon' => 'Honeymoon/Romance',
                                        'photography' => 'Photography Focus',
                                        'birding' => 'Bird Watching',
                                        'family' => 'Family Safari',
                                        'luxury' => 'Ultra-Luxury',
                                        'adventure' => 'Adventure/Active',
                                        'cultural' => 'Cultural Immersion',
                                        'conservation' => 'Conservation Focus',
                                        'wellness' => 'Wellness/Spa',
                                        'migration' => 'Great Migration',
                                    ])
                                    ->columns(3),

                                Forms\Components\CheckboxList::make('wildlife_preferences')
                                    ->options([
                                        'big_five' => 'Big Five',
                                        'gorillas' => 'Gorillas',
                                        'chimpanzees' => 'Chimpanzees',
                                        'migration' => 'Great Migration',
                                        'predators' => 'Predators',
                                        'elephants' => 'Elephants',
                                        'birds' => 'Birds',
                                        'rare_species' => 'Rare Species',
                                        'marine_life' => 'Marine Life',
                                        'all_wildlife' => 'All Wildlife',
                                    ])
                                    ->columns(3),
                            ]),

                        // Budget & Additional Info Tab
                        Forms\Components\Tabs\Tab::make('Additional Info')
                            ->icon('heroicon-o-currency-dollar')
                            ->schema([
                                Forms\Components\Select::make('budget_range')
                                    ->options([
                                        'budget' => 'Budget ($200-400/night)',
                                        'mid' => 'Mid-Range ($400-800/night)',
                                        'luxury' => 'Luxury ($800-1500/night)',
                                        'ultra' => 'Ultra-Luxury ($1500+/night)',
                                        'flexible' => 'Flexible',
                                    ]),
                                Forms\Components\Textarea::make('dietary_requirements')
                                    ->rows(3)
                                    ->columnSpanFull(),
                                Forms\Components\Textarea::make('mobility_considerations')
                                    ->rows(3)
                                    ->columnSpanFull(),
                                Forms\Components\Textarea::make('additional_requests')
                                    ->rows(4)
                                    ->columnSpanFull(),
                            ]),

                        // Admin Management Tab
                        Forms\Components\Tabs\Tab::make('Admin')
                            ->icon('heroicon-o-cog')
                            ->schema([
                                Forms\Components\Section::make('Status & Assignment')
                                    ->schema([
                                        Forms\Components\Select::make('status')
                                            ->options([
                                                'new' => 'New',
                                                'reviewing' => 'Reviewing',
                                                'quoted' => 'Quoted',
                                                'negotiating' => 'Negotiating',
                                                'accepted' => 'Accepted',
                                                'rejected' => 'Rejected',
                                                'expired' => 'Expired',
                                            ])
                                            ->required()
                                            ->default('new'),
                                        Forms\Components\Select::make('priority')
                                            ->options([
                                                'low' => 'Low',
                                                'medium' => 'Medium',
                                                'high' => 'High',
                                                'urgent' => 'Urgent',
                                            ])
                                            ->required()
                                            ->default('medium'),
                                        Forms\Components\Select::make('assigned_to')
                                            ->label('Assign To')
                                            ->options(User::pluck('name', 'id'))
                                            ->searchable(),
                                    ])->columns(3),

                                Forms\Components\Section::make('Quote Details')
                                    ->schema([
                                        Forms\Components\TextInput::make('quoted_price')
                                            ->numeric()
                                            ->prefix('$')
                                            ->maxLength(255),
                                        Forms\Components\Select::make('quoted_currency')
                                            ->options([
                                                'USD' => 'USD',
                                                'EUR' => 'EUR',
                                                'GBP' => 'GBP',
                                                'KES' => 'KES',
                                                'TZS' => 'TZS',
                                                'ZAR' => 'ZAR',
                                            ])
                                            ->default('USD'),
                                    ])->columns(2),

                                Forms\Components\Section::make('Response & Notes')
                                    ->schema([
                                        Forms\Components\RichEditor::make('proposal_response')
                                            ->label('Proposal Response to Client')
                                            ->toolbarButtons([
                                                'bold',
                                                'bulletList',
                                                'italic',
                                                'orderedList',
                                                'redo',
                                                'undo',
                                            ])
                                            ->columnSpanFull(),
                                        Forms\Components\Textarea::make('admin_notes')
                                            ->label('Internal Admin Notes')
                                            ->rows(4)
                                            ->columnSpanFull(),
                                    ]),
                            ]),
                    ])
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('full_name')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                
                Tables\Columns\TextColumn::make('email')
                    ->searchable()
                    ->copyable()
                    ->icon('heroicon-m-envelope'),
                
                Tables\Columns\TextColumn::make('country')
                    ->searchable()
                    ->toggleable(),
                
                Tables\Columns\TextColumn::make('destination_names')
                    ->badge()
                    ->separator(',')
                    ->label('Destinations')
                    ->limit(2),
                
                Tables\Columns\TextColumn::make('travel_start_date')
                    ->date()
                    ->sortable()
                    ->toggleable(),
                
                Tables\Columns\TextColumn::make('total_travelers')
                    ->label('Travelers')
                    ->badge()
                    ->color('info')
                    ->toggleable(),
                
                Tables\Columns\TextColumn::make('budget_label')
                    ->label('Budget')
                    ->toggleable(),
                
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'new' => 'danger',
                        'reviewing' => 'warning',
                        'quoted' => 'info',
                        'negotiating' => 'warning',
                        'accepted' => 'success',
                        'rejected' => 'gray',
                        'expired' => 'gray',
                    })
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('priority')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'low' => 'gray',
                        'medium' => 'info',
                        'high' => 'warning',
                        'urgent' => 'danger',
                    })
                    ->sortable()
                    ->toggleable(),
                
                Tables\Columns\TextColumn::make('assignedUser.name')
                    ->label('Assigned To')
                    ->toggleable()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('days_old')
                    ->label('Age')
                    ->suffix(' days')
                    ->sortable(query: function (Builder $query, string $direction): Builder {
                        return $query->orderBy('created_at', $direction === 'asc' ? 'desc' : 'asc');
                    })
                    ->color(fn ($state): string => match (true) {
                        $state > 7 => 'danger',
                        $state > 3 => 'warning',
                        default => 'success',
                    }),
                
                Tables\Columns\IconColumn::make('is_new')
                    ->label('New')
                    ->boolean()
                    ->toggleable(),
                
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'new' => 'New',
                        'reviewing' => 'Reviewing',
                        'quoted' => 'Quoted',
                        'negotiating' => 'Negotiating',
                        'accepted' => 'Accepted',
                        'rejected' => 'Rejected',
                        'expired' => 'Expired',
                    ])
                    ->multiple(),
                
                Tables\Filters\SelectFilter::make('priority')
                    ->options([
                        'low' => 'Low',
                        'medium' => 'Medium',
                        'high' => 'High',
                        'urgent' => 'Urgent',
                    ])
                    ->multiple(),
                
                Tables\Filters\SelectFilter::make('assigned_to')
                    ->relationship('assignedUser', 'name')
                    ->searchable()
                    ->preload(),
                
                Tables\Filters\Filter::make('unviewed')
                    ->query(fn (Builder $query): Builder => $query->whereNull('viewed_at'))
                    ->toggle(),
                
                Tables\Filters\Filter::make('created_at')
                    ->form([
                        Forms\Components\DatePicker::make('created_from'),
                        Forms\Components\DatePicker::make('created_until'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['created_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['created_until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                            );
                    }),
                
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    
                    Tables\Actions\Action::make('markAsViewed')
                        ->icon('heroicon-o-eye')
                        ->color('info')
                        ->requiresConfirmation()
                        ->action(function (Proposal $record) {
                            $record->markAsViewed();
                            Notification::make()
                                ->success()
                                ->title('Marked as viewed')
                                ->send();
                        })
                        ->visible(fn (Proposal $record) => !$record->viewed_at),
                    
                    Tables\Actions\Action::make('assignToMe')
                        ->icon('heroicon-o-user-plus')
                        ->color('success')
                        ->action(function (Proposal $record) {
                            $record->assignTo(auth()->id());
                            Notification::make()
                                ->success()
                                ->title('Assigned to you')
                                ->send();
                        })
                        ->visible(fn (Proposal $record) => $record->assigned_to !== auth()->id()),
                    
                    Tables\Actions\Action::make('sendEmail')
                        ->icon('heroicon-o-envelope')
                        ->color('warning')
                        ->url(fn (Proposal $record): string => "mailto:{$record->email}")
                        ->openUrlInNewTab(),
                    
                    Tables\Actions\DeleteAction::make(),
                    Tables\Actions\ForceDeleteAction::make(),
                    Tables\Actions\RestoreAction::make(),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\BulkAction::make('assignTo')
                        ->icon('heroicon-o-user-plus')
                        ->form([
                            Forms\Components\Select::make('user_id')
                                ->label('Assign to')
                                ->options(User::pluck('name', 'id'))
                                ->required(),
                        ])
                        ->action(function ($records, array $data) {
                            $records->each->assignTo($data['user_id']);
                            Notification::make()
                                ->success()
                                ->title('Proposals assigned')
                                ->send();
                        }),
                    
                    Tables\Actions\BulkAction::make('updateStatus')
                        ->icon('heroicon-o-arrow-path')
                        ->form([
                            Forms\Components\Select::make('status')
                                ->options([
                                    'new' => 'New',
                                    'reviewing' => 'Reviewing',
                                    'quoted' => 'Quoted',
                                    'negotiating' => 'Negotiating',
                                    'accepted' => 'Accepted',
                                    'rejected' => 'Rejected',
                                    'expired' => 'Expired',
                                ])
                                ->required(),
                        ])
                        ->action(function ($records, array $data) {
                            $records->each->update(['status' => $data['status']]);
                            Notification::make()
                                ->success()
                                ->title('Status updated')
                                ->send();
                        }),
                    
Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc')
            ->poll('30s') // Auto-refresh every 30 seconds
            ->persistFiltersInSession()
            ->persistSearchInSession()
            ->persistColumnSearchesInSession();
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make('Contact Information')
                    ->schema([
                        Infolists\Components\TextEntry::make('full_name')
                            ->label('Full Name')
                            ->icon('heroicon-o-user'),
                        Infolists\Components\TextEntry::make('email')
                            ->icon('heroicon-o-envelope')
                            ->copyable(),
                        Infolists\Components\TextEntry::make('phone')
                            ->icon('heroicon-o-phone')
                            ->copyable(),
                        Infolists\Components\TextEntry::make('country')
                            ->icon('heroicon-o-globe-alt'),
                    ])
                    ->columns(2),

                Infolists\Components\Section::make('Trip Overview')
                    ->schema([
                        Infolists\Components\TextEntry::make('destination_names')
                            ->label('Destinations')
                            ->badge()
                            ->separator(','),
                        Infolists\Components\TextEntry::make('travel_start_date')
                            ->label('Start Date')
                            ->date(),
                        Infolists\Components\TextEntry::make('travel_end_date')
                            ->label('End Date')
                            ->date(),
                        Infolists\Components\TextEntry::make('trip_duration_days')
                            ->label('Duration')
                            ->suffix(' days')
                            ->badge()
                            ->color('info'),
                        Infolists\Components\TextEntry::make('duration')
                            ->label('Preferred Duration'),
                        Infolists\Components\TextEntry::make('adults')
                            ->badge()
                            ->color('success'),
                        Infolists\Components\TextEntry::make('children')
                            ->badge()
                            ->color('warning'),
                        Infolists\Components\TextEntry::make('children_ages')
                            ->label('Children Ages')
                            ->visible(fn ($record) => $record->children > 0),
                    ])
                    ->columns(3),

                Infolists\Components\Section::make('Accommodation Preferences')
                    ->schema([
                        Infolists\Components\TextEntry::make('accommodation_types')
                            ->label('Accommodation Types')
                            ->badge()
                            ->separator(',')
                            ->formatStateUsing(fn ($state) => match($state) {
                                'luxury_lodge' => 'Luxury Safari Lodge',
                                'luxury_tented' => 'Luxury Tented Camp',
                                'boutique_camp' => 'Boutique Tented Camp',
                                'mobile_camp' => 'Mobile Tented Camp',
                                'mid_range' => 'Mid-Range Lodge',
                                'budget_camp' => 'Budget Camping',
                                default => $state,
                            }),
                        Infolists\Components\TextEntry::make('room_configuration')
                            ->badge(),
                    ])
                    ->columns(2),

                Infolists\Components\Section::make('Activities & Interests')
                    ->schema([
                        Infolists\Components\TextEntry::make('activities')
                            ->badge()
                            ->separator(',')
                            ->color('success')
                            ->columnSpanFull(),
                        Infolists\Components\TextEntry::make('special_interests')
                            ->badge()
                            ->separator(',')
                            ->color('warning')
                            ->columnSpanFull(),
                        Infolists\Components\TextEntry::make('wildlife_preferences')
                            ->badge()
                            ->separator(',')
                            ->color('info')
                            ->columnSpanFull(),
                    ]),

                Infolists\Components\Section::make('Budget & Additional Information')
                    ->schema([
                        Infolists\Components\TextEntry::make('budget_label')
                            ->label('Budget Range')
                            ->badge()
                            ->color('success'),
                        Infolists\Components\TextEntry::make('dietary_requirements')
                            ->visible(fn ($record) => filled($record->dietary_requirements))
                            ->columnSpanFull(),
                        Infolists\Components\TextEntry::make('mobility_considerations')
                            ->visible(fn ($record) => filled($record->mobility_considerations))
                            ->columnSpanFull(),
                        Infolists\Components\TextEntry::make('additional_requests')
                            ->visible(fn ($record) => filled($record->additional_requests))
                            ->columnSpanFull(),
                    ]),

                Infolists\Components\Section::make('Admin Information')
                    ->schema([
                        Infolists\Components\TextEntry::make('status')
                            ->badge()
                            ->color(fn (string $state): string => match ($state) {
                                'new' => 'danger',
                                'reviewing' => 'warning',
                                'quoted' => 'info',
                                'negotiating' => 'warning',
                                'accepted' => 'success',
                                'rejected' => 'gray',
                                'expired' => 'gray',
                            }),
                        Infolists\Components\TextEntry::make('priority')
                            ->badge()
                            ->color(fn (string $state): string => match ($state) {
                                'low' => 'gray',
                                'medium' => 'info',
                                'high' => 'warning',
                                'urgent' => 'danger',
                            }),
                        Infolists\Components\TextEntry::make('assignedUser.name')
                            ->label('Assigned To')
                            ->default('Unassigned')
                            ->icon('heroicon-o-user'),
                        Infolists\Components\TextEntry::make('quoted_price')
                            ->money(fn ($record) => $record->quoted_currency)
                            ->visible(fn ($record) => filled($record->quoted_price)),
                        Infolists\Components\TextEntry::make('viewed_at')
                            ->dateTime()
                            ->placeholder('Not viewed yet'),
                        Infolists\Components\TextEntry::make('responded_at')
                            ->dateTime()
                            ->placeholder('Not responded yet'),
                        Infolists\Components\TextEntry::make('days_old')
                            ->label('Days Since Submission')
                            ->suffix(' days')
                            ->badge()
                            ->color(fn ($state): string => match (true) {
                                $state > 7 => 'danger',
                                $state > 3 => 'warning',
                                default => 'success',
                            }),
                        Infolists\Components\TextEntry::make('source')
                            ->badge(),
                    ])
                    ->columns(3),

                Infolists\Components\Section::make('Response & Notes')
                    ->schema([
                        Infolists\Components\TextEntry::make('proposal_response')
                            ->html()
                            ->visible(fn ($record) => filled($record->proposal_response))
                            ->columnSpanFull(),
                        Infolists\Components\TextEntry::make('admin_notes')
                            ->visible(fn ($record) => filled($record->admin_notes))
                            ->columnSpanFull(),
                    ])
                    ->collapsed(),

                Infolists\Components\Section::make('Metadata')
                    ->schema([
                        Infolists\Components\TextEntry::make('created_at')
                            ->dateTime(),
                        Infolists\Components\TextEntry::make('updated_at')
                            ->dateTime(),
                        Infolists\Components\TextEntry::make('ip_address')
                            ->copyable()
                            ->visible(fn ($record) => filled($record->ip_address)),
                    ])
                    ->columns(3)
                    ->collapsed(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProposals::route('/'),
            'create' => Pages\CreateProposal::route('/create'),
            'view' => Pages\ViewProposal::route('/{record}'),
            'edit' => Pages\EditProposal::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['full_name', 'email', 'phone', 'country'];
    }

    public static function getGlobalSearchResultDetails($record): array
    {
        return [
            'Email' => $record->email,
            'Country' => $record->country,
            'Status' => $record->status,
        ];
    }
}                    