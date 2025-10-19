<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CustomItineraryResource\Pages;
use App\Models\CustomItinerary;
use App\Models\Package;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Support\Enums\FontWeight;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Support\Colors\Color;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Support\Facades\Mail;

class CustomItineraryResource extends Resource
{
    protected static ?string $model = CustomItinerary::class;

    protected static ?string $navigationIcon = 'heroicon-o-map';
    
    protected static ?string $navigationLabel = 'Custom Itineraries';
    
    protected static ?string $pluralModelLabel = 'Custom Itineraries';
    
    protected static ?string $navigationGroup = 'Package Management';
    
    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Customer Information')
                    ->schema([
                        Forms\Components\TextInput::make('customer_name')
                            ->label('Customer Name')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('customer_email')
                            ->label('Customer Email')
                            ->email()
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('customer_phone')
                            ->label('Customer Phone')
                            ->tel()
                            ->maxLength(20),
                    ])->columns(3),

                Forms\Components\Section::make('Itinerary Details')
                    ->schema([
                        Forms\Components\Select::make('package_id')
                            ->label('Package')
                            ->relationship('package', 'title')
                            ->required()
                            ->searchable()
                            ->preload(),
                        Forms\Components\TextInput::make('reference_id')
                            ->label('Reference ID')
                            ->disabled()
                            ->dehydrated(false),
                        Forms\Components\Select::make('status')
                            ->label('Status')
                            ->options([
                                'pending_approval' => 'Pending Approval',
                                'approved' => 'Approved',
                                'rejected' => 'Rejected',
                                'needs_revision' => 'Needs Revision',
                            ])
                            ->required()
                            ->default('pending_approval'),
                    ])->columns(3),

                Forms\Components\Section::make('Day Details')
                    ->schema([
                        Forms\Components\TextInput::make('day_number')
                            ->label('Day Number')
                            ->numeric()
                            ->required()
                            ->minValue(1),
                        Forms\Components\TextInput::make('title')
                            ->label('Day Title')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Textarea::make('description')
                            ->label('Description')
                            ->required()
                            ->rows(3),
                    ])->columns(1),

                Forms\Components\Section::make('Preferences')
                    ->schema([
                        Forms\Components\TextInput::make('accommodation_preference')
                            ->label('Accommodation Preference')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('meals_preference')
                            ->label('Meals Preference')
                            ->maxLength(255),
                        Forms\Components\Textarea::make('activities_preference')
                            ->label('Activities Preference')
                            ->rows(2),
                        Forms\Components\Textarea::make('special_notes')
                            ->label('Special Notes')
                            ->rows(2),
                    ])->columns(2),

                Forms\Components\Section::make('Special Requests & Admin Notes')
                    ->schema([
                        Forms\Components\Textarea::make('special_requests')
                            ->label('Customer Special Requests')
                            ->rows(3)
                            ->disabled()
                            ->dehydrated(false),
                        Forms\Components\Textarea::make('admin_notes')
                            ->label('Admin Notes')
                            ->rows(3)
                            ->helperText('Internal notes for this custom itinerary'),
                    ])->columns(1),

                Forms\Components\Section::make('Review Information')
                    ->schema([
                        Forms\Components\DateTimePicker::make('submitted_at')
                            ->label('Submitted At')
                            ->disabled()
                            ->dehydrated(false),
                        Forms\Components\DateTimePicker::make('reviewed_at')
                            ->label('Reviewed At'),
                        Forms\Components\Select::make('reviewed_by')
                            ->label('Reviewed By')
                            ->relationship('reviewer', 'name')
                            ->searchable()
                            ->preload(),
                    ])->columns(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->query(
                // Group by reference_id to show one row per submission
                static::getModel()::query()
                    ->select('*')
                    ->whereIn('id', function ($query) {
                        $query->select(\DB::raw('MIN(id)'))
                            ->from('custom_itineraries')
                            ->groupBy('reference_id');
                    })
            )
            ->columns([
                Tables\Columns\TextColumn::make('reference_id')
                    ->label('Reference ID')
                    ->searchable()
                    ->sortable()
                    ->weight(FontWeight::Bold)
                    ->color(Color::Blue),
                    
                Tables\Columns\TextColumn::make('customer_name')
                    ->label('Customer')
                    ->searchable()
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('customer_email')
                    ->label('Email')
                    ->searchable()
                    ->copyable()
                    ->limit(25),
                    
                Tables\Columns\TextColumn::make('package.title')
                    ->label('Package')
                    ->searchable()
                    ->sortable()
                    ->limit(30),
                    
                Tables\Columns\BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'warning' => 'pending_approval',
                        'success' => 'approved',
                        'danger' => 'rejected',
                        'info' => 'needs_revision',
                    ])
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'pending_approval' => 'Pending',
                        'approved' => 'Approved',
                        'rejected' => 'Rejected',
                        'needs_revision' => 'Needs Revision',
                        default => $state,
                    }),
                    
                Tables\Columns\TextColumn::make('total_days')
                    ->label('Days')
                    ->getStateUsing(function ($record) {
                        return CustomItinerary::where('reference_id', $record->reference_id)->count();
                    })
                    ->badge()
                    ->color(Color::Gray),
                    
                Tables\Columns\TextColumn::make('submitted_at')
                    ->label('Submitted')
                    ->dateTime('M j, Y g:i A')
                    ->sortable(),
                    
                Tables\Columns\IconColumn::make('customer_phone')
                    ->label('Phone')
                    ->boolean()
                    ->trueIcon('heroicon-o-phone')
                    ->falseIcon('heroicon-o-phone-x-mark')
                    ->trueColor('success')
                    ->falseColor('gray'),
                    
                Tables\Columns\IconColumn::make('special_requests')
                    ->label('Special Requests')
                    ->boolean()
                    ->trueIcon('heroicon-o-chat-bubble-bottom-center-text')
                    ->falseIcon('heroicon-o-minus')
                    ->trueColor('info')
                    ->falseColor('gray'),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'pending_approval' => 'Pending Approval',
                        'approved' => 'Approved',
                        'rejected' => 'Rejected',
                        'needs_revision' => 'Needs Revision',
                    ])
                    ->default('pending_approval'),
                    
                SelectFilter::make('package_id')
                    ->label('Package')
                    ->relationship('package', 'title')
                    ->searchable()
                    ->preload(),
                    
                Tables\Filters\Filter::make('submitted_today')
                    ->label('Submitted Today')
                    ->query(fn (Builder $query): Builder => $query->whereDate('submitted_at', today())),
                    
                Tables\Filters\Filter::make('has_special_requests')
                    ->label('Has Special Requests')
                    ->query(fn (Builder $query): Builder => $query->whereNotNull('special_requests')->where('special_requests', '!=', '')),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    
                    Tables\Actions\Action::make('approve')
                        ->label('Approve')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->requiresConfirmation()
                        ->modalHeading('Approve Custom Itinerary')
                        ->modalDescription('Are you sure you want to approve this custom itinerary? The customer will be notified via email.')
                        ->action(function (CustomItinerary $record) {
                            // Update all days for this reference ID
                            CustomItinerary::where('reference_id', $record->reference_id)
                                ->update([
                                    'status' => 'approved',
                                    'reviewed_at' => now(),
                                    'reviewed_by' => auth()->id(),
                                ]);
                            
                            // Send approval email to customer
                            try {
                                Mail::send('emails.custom-itinerary-approved', [
                                    'customer_name' => $record->customer_name,
                                    'reference_id' => $record->reference_id,
                                    'package_name' => $record->package->title,
                                ], function ($message) use ($record) {
                                    $message->to($record->customer_email, $record->customer_name)
                                            ->subject('Custom Itinerary Approved - ' . $record->reference_id)
                                            ->from(config('mail.from.address'), config('mail.from.name'));
                                });
                            } catch (\Exception $e) {
                                \Log::error('Failed to send approval email: ' . $e->getMessage());
                            }
                        })
                        ->visible(fn (CustomItinerary $record): bool => $record->status !== 'approved'),
                        
                    Tables\Actions\Action::make('needs_revision')
                        ->label('Needs Revision')
                        ->icon('heroicon-o-chat-bubble-bottom-center-text')
                        ->color('info')
                        ->requiresConfirmation()
                        ->modalHeading('Mark as Needs Revision')
                        ->modalDescription('Are you sure you want to mark this custom itinerary as needing revision? The customer will be notified via email.')
                        ->action(function (CustomItinerary $record) {
                            // Update all days for this reference ID
                            CustomItinerary::where('reference_id', $record->reference_id)
                                ->update([
                                    'status' => 'needs_revision',
                                    'reviewed_at' => now(),
                                    'reviewed_by' => auth()->id(),
                                ]);
                            
                            // Send revision email to customer
                            try {
                                Mail::send('emails.custom-itinerary-rejected', [
                                    'customer_name' => $record->customer_name,
                                    'reference_id' => $record->reference_id,
                                    'package_name' => $record->package->title,
                                ], function ($message) use ($record) {
                                    $message->to($record->customer_email, $record->customer_name)
                                            ->subject('Custom Itinerary Update - ' . $record->reference_id)
                                            ->from(config('mail.from.address'), config('mail.from.name'));
                                });
                            } catch (\Exception $e) {
                                \Log::error('Failed to send revision email: ' . $e->getMessage());
                            }
                        })
                        ->visible(fn (CustomItinerary $record): bool => !in_array($record->status, ['approved', 'needs_revision'])),
                        
                    Tables\Actions\Action::make('reject')
                        ->label('Reject')
                        ->icon('heroicon-o-x-circle')
                        ->color('danger')
                        ->requiresConfirmation()
                        ->modalHeading('Reject Custom Itinerary')
                        ->modalDescription('Are you sure you want to reject this custom itinerary? This action cannot be undone and the customer will be notified.')
                        ->action(function (CustomItinerary $record) {
                            // Update all days for this reference ID
                            CustomItinerary::where('reference_id', $record->reference_id)
                                ->update([
                                    'status' => 'rejected',
                                    'reviewed_at' => now(),
                                    'reviewed_by' => auth()->id(),
                                ]);
                            
                            // Send rejection email to customer
                            try {
                                Mail::send('emails.custom-itinerary-rejected', [
                                    'customer_name' => $record->customer_name,
                                    'reference_id' => $record->reference_id,
                                    'package_name' => $record->package->title,
                                ], function ($message) use ($record) {
                                    $message->to($record->customer_email, $record->customer_name)
                                            ->subject('Custom Itinerary Rejected - ' . $record->reference_id)
                                            ->from(config('mail.from.address'), config('mail.from.name'));
                                });
                            } catch (\Exception $e) {
                                \Log::error('Failed to send rejection email: ' . $e->getMessage());
                            }
                        })
                        ->visible(fn (CustomItinerary $record): bool => $record->status === 'pending_approval'),
                        
                    Tables\Actions\DeleteAction::make()
                        ->label('Delete Submission')
                        ->requiresConfirmation()
                        ->modalHeading('Delete Custom Itinerary Submission')
                        ->modalDescription('Are you sure you want to delete this entire custom itinerary submission? This will remove all days and cannot be undone.')
                        ->action(function (CustomItinerary $record) {
                            // Delete all days for this reference ID
                            CustomItinerary::where('reference_id', $record->reference_id)->delete();
                        })
                        ->color('danger'),
                ])
                ->label('Actions')
                ->icon('heroicon-m-ellipsis-vertical')
                ->size('sm')
                ->color('gray')
                ->button(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    
                    Tables\Actions\BulkAction::make('approve_selected')
                        ->label('Approve Selected')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->requiresConfirmation()
                        ->action(function ($records) {
                            foreach ($records as $record) {
                                $record->update([
                                    'status' => 'approved',
                                    'reviewed_at' => now(),
                                    'reviewed_by' => auth()->id(),
                                ]);
                            }
                        }),
                ]),
            ])
            ->defaultSort('submitted_at', 'desc')
            ->poll('30s')
            ->deferLoading();
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
            'index' => Pages\ListCustomItineraries::route('/'),
            'create' => Pages\CreateCustomItinerary::route('/create'),
            'view' => Pages\ViewCustomItinerary::route('/{record}'),
            'edit' => Pages\EditCustomItinerary::route('/{record}/edit'),
        ];
    }
    
    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('status', 'pending_approval')
            ->select('reference_id')
            ->distinct()
            ->count();
    }
    
    public static function getNavigationBadgeColor(): string|array|null
    {
        $count = static::getModel()::where('status', 'pending_approval')
            ->select('reference_id')
            ->distinct()
            ->count();
        return $count > 0 ? 'warning' : 'success';
    }
}
