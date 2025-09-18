<?php

namespace App\Filament\Resources;

use App\Models\Inquiry;
use Filament\Resources\Resource;
use App\Filament\Resources\InquiryResource\Pages;
use Filament\Tables;
use Filament\Forms;

class InquiryResource extends Resource
{
    protected static ?string $model = Inquiry::class;
    protected static ?string $navigationGroup = 'Customer Management';
    protected static ?string $navigationIcon = 'heroicon-o-envelope';
    protected static ?int $navigationSort = 5;
    protected static ?string $navigationLabel = 'Inquiries';
    protected static ?string $modelLabel = 'Inquiry';
    protected static ?string $pluralModelLabel = 'Inquiries';

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Contact Information')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(100)
                            ->placeholder('Customer full name'),
                        Forms\Components\TextInput::make('email')
                            ->email()
                            ->required()
                            ->maxLength(100)
                            ->placeholder('Customer email address'),
                        Forms\Components\TextInput::make('phone')
                            ->tel()
                            ->maxLength(20)
                            ->placeholder('Customer phone number'),
                        Forms\Components\TextInput::make('subject')
                            ->maxLength(200)
                            ->placeholder('Inquiry subject'),
                        Forms\Components\Select::make('package_id')
                            ->label('Related Package')
                            ->relationship('package', 'title')
                            ->searchable()
                            ->preload()
                            ->nullable(),
                    ])->columns(2),

                Forms\Components\Section::make('Inquiry Details')
                    ->schema([
                        Forms\Components\Textarea::make('message')
                            ->required()
                            ->rows(4)
                            ->placeholder('Customer message or inquiry...')
                            ->columnSpanFull(),
                        Forms\Components\Select::make('status')
                            ->options([
                                'new' => 'New',
                                'responded' => 'Responded',
                                'closed' => 'Closed',
                            ])
                            ->default('new')
                            ->required()
                            ->native(false)
                            ->prefixIcon('heroicon-m-flag')
                            ->suffixIcon('heroicon-m-chevron-down'),
                    ])->columns(2),
            ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->weight('medium'),
                Tables\Columns\TextColumn::make('email')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->copyMessage('Email copied!')
                    ->icon('heroicon-m-envelope'),
                Tables\Columns\TextColumn::make('phone')
                    ->searchable()
                    ->placeholder('No phone')
                    ->copyable()
                    ->copyMessage('Phone copied!')
                    ->icon('heroicon-m-phone'),
                Tables\Columns\TextColumn::make('subject')
                    ->searchable()
                    ->limit(30)
                    ->placeholder('General Inquiry'),
                Tables\Columns\TextColumn::make('package.title')
                    ->label('Package')
                    ->limit(30)
                    ->placeholder('No package')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('message_excerpt')
                    ->label('Message')
                    ->getStateUsing(fn (Inquiry $record) => $record->message_excerpt)
                    ->wrap()
                    ->limit(50)
                    ->tooltip(function (Tables\Columns\TextColumn $column): ?string {
                        $record = $column->getRecord();
                        return $record->message;
                    }),
                Tables\Columns\SelectColumn::make('status')
                    ->options([
                        'new' => 'New',
                        'responded' => 'Responded',
                        'closed' => 'Closed',
                    ])
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Received')
                    ->dateTime('M j, Y g:i A')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'new' => 'New',
                        'responded' => 'Responded',
                        'closed' => 'Closed',
                    ])
                    ->multiple()
                    ->default(['new']),
                Tables\Filters\Filter::make('recent')
                    ->label('Recent (Last 7 days)')
                    ->query(fn ($query) => $query->where('created_at', '>=', now()->subDays(7)))
                    ->toggle(),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\Action::make('mark_responded')
                        ->label('Mark as Responded')
                        ->icon('heroicon-o-check-circle')
                        ->color('info')
                        ->action(fn (Inquiry $record) => $record->markAsResponded())
                        ->visible(fn (Inquiry $record) => !$record->isResponded() && !$record->isClosed())
                        ->requiresConfirmation(),
                    Tables\Actions\Action::make('mark_closed')
                        ->label('Mark as Closed')
                        ->icon('heroicon-o-x-circle')
                        ->color('success')
                        ->action(fn (Inquiry $record) => $record->markAsClosed())
                        ->visible(fn (Inquiry $record) => !$record->isClosed())
                        ->requiresConfirmation(),
                    Tables\Actions\DeleteAction::make(),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\BulkAction::make('mark_responded')
                        ->label('Mark as Responded')
                        ->icon('heroicon-o-check-circle')
                        ->color('info')
                        ->action(fn ($records) => $records->each->markAsResponded())
                        ->requiresConfirmation(),
                    Tables\Actions\BulkAction::make('mark_closed')
                        ->label('Mark as Closed')
                        ->icon('heroicon-o-x-circle')
                        ->color('success')
                        ->action(fn ($records) => $records->each->markAsClosed())
                        ->requiresConfirmation(),
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListInquiries::route('/'),
            'create' => Pages\CreateInquiry::route('/create'),
            'view' => Pages\ViewInquiry::route('/{record}'),
            'edit' => Pages\EditInquiry::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('status', 'new')->count();
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return static::getNavigationBadge() > 0 ? 'warning' : 'success';
    }
}
