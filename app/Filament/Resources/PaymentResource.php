<?php

namespace App\Filament\Resources;

use App\Models\Payment;
use App\Models\Booking;
use Filament\Resources\Resource;
use App\Filament\Resources\PaymentResource\Pages;
use Filament\Tables;
use Filament\Forms;

class PaymentResource extends Resource
{
    protected static ?string $model = Payment::class;
    protected static ?string $navigationGroup = 'Customer Management';
    protected static ?string $navigationIcon = 'heroicon-o-credit-card';
    protected static ?int $navigationSort = 3;

    public static function canViewAny(): bool
    {
    $user = filament()->auth()->user();
    return $user && $user->role === 'admin';
    }

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('booking_id')
                    ->label('Booking')
                    ->options(Booking::with('customer')->get()->pluck('booking_reference', 'id')->toArray())
                    ->required()
                    ->searchable()
                    ->getOptionLabelFromRecordUsing(fn (Booking $record) => 
                        "{$record->booking_reference} - {$record->customer->name}"
                    ),
                Forms\Components\TextInput::make('amount')
                    ->required()
                    ->numeric()
                    ->prefix('$')
                    ->step(0.01),
                Forms\Components\Select::make('payment_method')
                    ->label('Payment Method')
                    ->options([
                        'mpesa' => 'M-Pesa',
                        'card' => 'Credit/Debit Card',
                        'bank_transfer' => 'Bank Transfer',
                    ])
                    ->required()
                    ->native(false),
                Forms\Components\Select::make('payment_status')
                    ->label('Payment Status')
                    ->options([
                        'pending' => 'Pending',
                        'completed' => 'Completed',
                        'failed' => 'Failed',
                    ])
                    ->default('pending')
                    ->required()
                    ->native(false)
                    ->reactive()
                    ->afterStateUpdated(function (callable $set, $state) {
                        if ($state === 'completed') {
                            $set('paid_at', now());
                        } elseif ($state === 'pending' || $state === 'failed') {
                            $set('paid_at', null);
                        }
                    }),
                Forms\Components\Hidden::make('transaction_id')
                    ->default(fn () => Payment::generateTransactionId()),
                Forms\Components\DateTimePicker::make('paid_at')
                    ->label('Paid At')
                    ->nullable()
                    ->visible(fn (callable $get) => $get('payment_status') === 'completed'),
            ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('transaction_id')
                    ->label('Transaction ID')
                    ->searchable()
                    ->copyable(),
                Tables\Columns\TextColumn::make('booking.booking_reference')
                    ->label('Booking Reference')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('booking.customer.name')
                    ->label('Customer')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('amount')
                    ->money('USD')
                    ->sortable(),
                Tables\Columns\TextColumn::make('payment_method')
                    ->label('Payment Method')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'mpesa' => 'success',
                        'card' => 'info',
                        'bank_transfer' => 'warning',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'mpesa' => 'M-Pesa',
                        'card' => 'Card',
                        'bank_transfer' => 'Bank Transfer',
                        default => ucfirst($state),
                    }),
                Tables\Columns\TextColumn::make('payment_status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'completed' => 'success',
                        'failed' => 'danger',
                    })
                    ->formatStateUsing(fn (string $state): string => ucfirst($state)),
                Tables\Columns\TextColumn::make('paid_at')
                    ->label('Paid At')
                    ->dateTime()
                    ->sortable()
                    ->placeholder('Not paid'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('payment_status')
                    ->label('Payment Status')
                    ->options([
                        'pending' => 'Pending',
                        'completed' => 'Completed',
                        'failed' => 'Failed',
                    ]),
                Tables\Filters\SelectFilter::make('payment_method')
                    ->label('Payment Method')
                    ->options([
                        'mpesa' => 'M-Pesa',
                        'card' => 'Credit/Debit Card',
                        'bank_transfer' => 'Bank Transfer',
                    ]),
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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPayments::route('/'),
            'create' => Pages\CreatePayment::route('/create'),
            'view' => Pages\ViewPayment::route('/{record}'),
            'edit' => Pages\EditPayment::route('/{record}/edit'),
        ];
    }
}
