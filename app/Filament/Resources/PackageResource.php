<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PackageResource\Pages;
use App\Filament\Resources\PackageResource\RelationManagers;
use App\Models\Package;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PackageResource extends Resource
{
    protected static ?string $model = Package::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Package Management';

    protected static ?int $navigationSort = 1;

    public static function canViewAny(): bool
    {
    $user = filament()->auth()->user();
    return $user && in_array($user->role, ['admin', 'portal_user']);
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Package Information')
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->required()
                            ->maxLength(200)
                            ->columnSpan(2),
                        Forms\Components\TextInput::make('theme')
                            ->label('Theme')
                            ->helperText('e.g., "Dance with the Tide", "Hidden Stories of the Coast"')
                            ->maxLength(100),
                        Forms\Components\TextInput::make('tagline')
                            ->label('Tagline')
                            ->helperText('e.g., "Two days, one ocean, infinite memories."')
                            ->maxLength(200),
                    ])->columns(3),
                
                Forms\Components\Textarea::make('description')
                    ->label('Description')
                    ->rows(4)
                    ->columnSpanFull(),
                
                Forms\Components\Section::make('Highlights')
                    ->schema([
                        Forms\Components\Repeater::make('highlights')
                            ->schema([
                                Forms\Components\TextInput::make('emoji')
                                    ->label('Emoji')
                                    ->maxLength(10)
                                    ->placeholder('🌅')
                                    ->helperText('Single emoji'),
                                Forms\Components\Textarea::make('text')
                                    ->label('Highlight Text')
                                    ->required()
                                    ->rows(2)
                                    ->placeholder('Dhow cruise under a tangerine sunset'),
                            ])
                            ->columns(2)
                            ->itemLabel(fn (array $state): ?string => $state['text'] ?? null)
                            ->defaultItems(0)
                            ->collapsible(),
                    ])->columnSpanFull(),
                
                Forms\Components\Section::make('Package Details')
                    ->schema([
                        Forms\Components\TextInput::make('duration_days')
                            ->required()
                            ->numeric()
                            ->label('Duration (Days)'),
                        Forms\Components\TextInput::make('price_usd')
                            ->required()
                            ->numeric()
                            ->prefix('$')
                            ->label('Price (USD)'),
                        Forms\Components\TextInput::make('max_participants')
                            ->required()
                            ->numeric()
                            ->default(8)
                            ->label('Max Participants'),
                        Forms\Components\FileUpload::make('image_url')
                            ->image()
                            ->label('Package Image')
                            ->directory('packages'),
                        Forms\Components\Toggle::make('is_active')
                            ->label('Active')
                            ->default(true),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image_url')
                    ->circular()
                    ->size(50),
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->weight('bold'),
                Tables\Columns\TextColumn::make('theme')
                    ->label('Theme')
                    ->searchable()
                    ->limit(30),
                Tables\Columns\TextColumn::make('tagline')
                    ->label('Tagline')
                    ->limit(40)
                    ->wrap(),
                Tables\Columns\TextColumn::make('duration_days')
                    ->label('Days')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('price_usd')
                    ->label('Price')
                    ->numeric()
                    ->sortable()
                    ->prefix('$'),
                Tables\Columns\TextColumn::make('max_participants')
                    ->label('Max')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\ToggleColumn::make('is_active')
                    ->label('Active'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
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

    public static function getRelations(): array
    {
        return [
            RelationManagers\DestinationsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPackages::route('/'),
            'create' => Pages\CreatePackage::route('/create'),
            'view' => Pages\ViewPackage::route('/{record}'),
            'edit' => Pages\EditPackage::route('/{record}/edit'),
        ];
    }
}
