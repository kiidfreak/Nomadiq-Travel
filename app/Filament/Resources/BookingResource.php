<?php

namespace App\Filament\Resources;

use App\Models\Booking;
use App\Models\Customer;
use App\Models\Package;
use Filament\Resources\Resource;
use App\Filament\Resources\BookingResource\Pages;
use Filament\Tables;
use Filament\Forms;
use Filament\Infolists;
use Filament\Infolists\Infolist;

class BookingResource extends Resource
{
    protected static ?string $model = Booking::class;
    protected static ?string $navigationGroup = 'Customer Management';
    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';
    protected static ?int $navigationSort = 2;

     public static function canViewAny(): bool
    {
    $user = filament()->auth()->user();
    return $user && in_array($user->role, ['admin', 'portal_user']);
    }

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('customer_id')
                    ->label('Customer')
                    ->options(Customer::whereNotNull('name')->pluck('name', 'id')->toArray())
                    ->required()
                    ->searchable(),
                Forms\Components\Select::make('package_id')
                    ->label('Package')
                    ->options(Package::whereNotNull('title')->pluck('title', 'id')->toArray())
                    ->required()
                    ->searchable()
                    ->reactive()
                    ->afterStateUpdated(fn (callable $set, callable $get) => self::updateTotalAmount($set, $get)),
                Forms\Components\Hidden::make('booking_reference')
                    ->default(fn () => Booking::generateBookingReference()),
                Forms\Components\DatePicker::make('start_date')
                    ->required(),
                Forms\Components\TextInput::make('number_of_people')
                    ->required()
                    ->numeric()
                    ->minValue(1)
                    ->reactive()
                    ->afterStateUpdated(fn (callable $set, callable $get) => self::updateTotalAmount($set, $get)),
                Forms\Components\TextInput::make('total_amount')
                    ->required()
                    ->numeric()
                    ->prefix('$')
                    ->disabled()
                    ->dehydrated(),
                Forms\Components\Select::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'confirmed' => 'Confirmed',
                        'cancelled' => 'Cancelled',
                    ])
                    ->default('pending')
                    ->required()
                    ->native(false)
                    ->prefixIcon('heroicon-m-clock')
                    ->suffixIcon('heroicon-m-chevron-down'),
                Forms\Components\MarkdownEditor::make('special_requests')
                    ->label('Special Requests')
                    ->columnSpanFull()
                    ->toolbarButtons([
                        'bold',
                        'italic',
                        'link',
                        'bulletList',
                        'orderedList',
                        'heading',
                        'blockquote',
                        'codeBlock',
                    ])
                    ->placeholder('Enter any special requests or requirements for this booking...'),
            ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->modifyQueryUsing(fn ($query) => $query->with(['payments']))
            ->columns([
                Tables\Columns\TextColumn::make('booking_reference')
                    ->searchable(),
                Tables\Columns\TextColumn::make('customer.name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('package.title')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('start_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('number_of_people')
                    ->sortable(),
                Tables\Columns\TextColumn::make('total_amount')
                    ->money('USD')
                    ->sortable(),
                Tables\Columns\TextColumn::make('total_paid')
                    ->label('Paid')
                    ->money('USD')
                    ->getStateUsing(function (Booking $record) {
                        return $record->payments()
                            ->where('payment_status', 'completed')
                            ->sum('amount');
                    })
                    ->color('success'),
                Tables\Columns\TextColumn::make('balance')
                    ->label('Balance')
                    ->money('USD')
                    ->getStateUsing(function (Booking $record) {
                        $totalPaid = $record->payments()
                            ->where('payment_status', 'completed')
                            ->sum('amount');
                        return $record->total_amount - $totalPaid;
                    })
                    ->color(function (Booking $record) {
                        $totalPaid = $record->payments()
                            ->where('payment_status', 'completed')
                            ->sum('amount');
                        $balance = $record->total_amount - $totalPaid;
                        
                        return match (true) {
                            $balance > 0 => 'danger',   // Outstanding balance
                            $balance == 0 => 'success', // Fully paid
                            $balance < 0 => 'warning',  // Overpaid
                        };
                    }),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'confirmed' => 'success',
                        'cancelled' => 'danger',
                    })
                    ->formatStateUsing(fn (string $state): string => ucfirst($state)),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'confirmed' => 'Confirmed',
                        'cancelled' => 'Cancelled',
                    ]),
                Tables\Filters\Filter::make('payment_status')
                    ->form([
                        Forms\Components\Select::make('payment_status')
                            ->label('Payment Status')
                            ->options([
                                'fully_paid' => 'Fully Paid',
                                'partial_payment' => 'Partial Payment',
                                'unpaid' => 'Unpaid',
                                'overpaid' => 'Overpaid',
                            ]),
                    ])
                    ->query(function ($query, array $data) {
                        if (!$data['payment_status']) {
                            return $query;
                        }

                        switch ($data['payment_status']) {
                            case 'fully_paid':
                                return $query->whereRaw('total_amount <= (SELECT COALESCE(SUM(amount), 0) FROM payments WHERE booking_id = bookings.id AND payment_status = "completed")');
                            case 'partial_payment':
                                return $query->whereRaw('total_amount > (SELECT COALESCE(SUM(amount), 0) FROM payments WHERE booking_id = bookings.id AND payment_status = "completed") AND (SELECT COALESCE(SUM(amount), 0) FROM payments WHERE booking_id = bookings.id AND payment_status = "completed") > 0');
                            case 'unpaid':
                                return $query->whereRaw('(SELECT COALESCE(SUM(amount), 0) FROM payments WHERE booking_id = bookings.id AND payment_status = "completed") = 0');
                            case 'overpaid':
                                return $query->whereRaw('total_amount < (SELECT COALESCE(SUM(amount), 0) FROM payments WHERE booking_id = bookings.id AND payment_status = "completed")');
                            default:
                                return $query;
                        }
                    }),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make('Booking Details')
                    ->schema([
                        Infolists\Components\TextEntry::make('booking_reference'),
                        Infolists\Components\TextEntry::make('customer.name'),
                        Infolists\Components\TextEntry::make('package.title'),
                        Infolists\Components\TextEntry::make('start_date')->date(),
                        Infolists\Components\TextEntry::make('number_of_people'),
                        Infolists\Components\TextEntry::make('status')->badge(),
                    ])->columns(2),
                
                Infolists\Components\Section::make('Payment Summary')
                    ->schema([
                        Infolists\Components\TextEntry::make('total_amount')
                            ->money('USD')
                            ->label('Total Amount'),
                        Infolists\Components\TextEntry::make('total_paid')
                            ->money('USD')
                            ->label('Amount Paid')
                            ->getStateUsing(function (Booking $record) {
                                return $record->payments()
                                    ->where('payment_status', 'completed')
                                    ->sum('amount');
                            }),
                        Infolists\Components\TextEntry::make('balance')
                            ->money('USD')
                            ->label('Balance')
                            ->getStateUsing(function (Booking $record) {
                                $totalPaid = $record->payments()
                                    ->where('payment_status', 'completed')
                                    ->sum('amount');
                                return $record->total_amount - $totalPaid;
                            })
                            ->color(function (Booking $record) {
                                $totalPaid = $record->payments()
                                    ->where('payment_status', 'completed')
                                    ->sum('amount');
                                $balance = $record->total_amount - $totalPaid;
                                
                                return match (true) {
                                    $balance > 0 => 'danger',
                                    $balance == 0 => 'success',
                                    $balance < 0 => 'warning',
                                };
                            }),
                    ])->columns(3),

                Infolists\Components\Section::make('Special Requests')
                    ->schema([
                        Infolists\Components\TextEntry::make('special_requests')
                            ->markdown()
                            ->columnSpanFull(),
                    ])
                    ->visible(fn (Booking $record) => !empty($record->special_requests)),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBookings::route('/'),
            'create' => Pages\CreateBooking::route('/create'),
            'view' => Pages\ViewBooking::route('/{record}'),
            'edit' => Pages\EditBooking::route('/{record}/edit'),
        ];
    }

    /**
     * Helper method to update total amount in the form
     */
    private static function updateTotalAmount(callable $set, callable $get): void
    {
        $packageId = $get('package_id');
        $numberOfPeople = $get('number_of_people');

        if ($packageId && $numberOfPeople) {
            $package = Package::find($packageId);
            if ($package) {
                $totalAmount = $package->price_usd * $numberOfPeople;
                $set('total_amount', number_format($totalAmount, 2, '.', ''));
            }
        }
    }
}
