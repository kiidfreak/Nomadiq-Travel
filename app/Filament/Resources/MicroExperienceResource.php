<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MicroExperienceResource\Pages;
use App\Filament\Resources\MicroExperienceResource\RelationManagers;
use App\Models\MicroExperience;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MicroExperienceResource extends Resource
{
    protected static ?string $model = MicroExperience::class;

    protected static ?string $navigationIcon = 'heroicon-o-sparkles';
    protected static ?string $navigationGroup = 'Package Management';
    protected static ?int $navigationSort = 6;
    protected static ?string $navigationLabel = 'Micro Experiences';

    public static function canViewAny(): bool
    {
        $user = filament()->auth()->user();
        return $user && in_array($user->role, ['admin', 'portal_user']);
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Experience Information')
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->required()
                            ->maxLength(200)
                            ->placeholder('e.g., Sunrise Yoga on the Sand Dunes'),
                        Forms\Components\TextInput::make('emoji')
                            ->label('Emoji Icon')
                            ->maxLength(10)
                            ->placeholder('🌅')
                            ->helperText('Single emoji to represent this experience'),
                        Forms\Components\Select::make('category')
                            ->label('Category')
                            ->options([
                                'wellness' => 'Wellness',
                                'culture' => 'Culture',
                                'adventure' => 'Adventure',
                                'nature' => 'Nature',
                                'food' => 'Food',
                            ])
                            ->required()
                            ->native(false),
                    ])->columns(3),
                
                Forms\Components\Textarea::make('description')
                    ->label('Description')
                    ->required()
                    ->rows(4)
                    ->placeholder('Describe this micro experience...')
                    ->columnSpanFull(),
                
                Forms\Components\Section::make('Experience Details')
                    ->schema([
                        Forms\Components\TextInput::make('price_usd')
                            ->label('Price (USD)')
                            ->numeric()
                            ->prefix('$')
                            ->helperText('Optional - leave empty for free experiences'),
                        Forms\Components\TextInput::make('duration_hours')
                            ->label('Duration (Hours)')
                            ->numeric()
                            ->helperText('How long does this experience take?'),
                        Forms\Components\TextInput::make('location')
                            ->label('Location')
                            ->maxLength(200)
                            ->placeholder('e.g., Watamu Beach, Lamu Old Town'),
                        Forms\Components\FileUpload::make('image_url')
                            ->label('Experience Image')
                            ->image()
                            ->directory('micro-experiences')
                            ->imageResizeMode('cover')
                            ->imageCropAspectRatio('16:9'),
                        Forms\Components\TextInput::make('sort_order')
                            ->label('Sort Order')
                            ->numeric()
                            ->default(0)
                            ->helperText('Lower numbers appear first'),
                        Forms\Components\Toggle::make('is_active')
                            ->label('Active')
                            ->default(true),
                    ])->columns(2),
                
                Forms\Components\Section::make('Package Availability')
                    ->schema([
                        Forms\Components\CheckboxList::make('available_packages')
                            ->label('Available for Packages')
                            ->options(\App\Models\Package::where('is_active', true)->pluck('title', 'id')->toArray())
                            ->helperText('Select which packages this experience can be added to')
                            ->searchable()
                            ->bulkToggleable()
                            ->deserializeStateUsing(fn ($state) => is_array($state) ? $state : json_decode($state ?? '[]', true))
                            ->dehydrateStateUsing(fn ($state) => is_array($state) ? $state : [])
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('emoji')
                    ->label('')
                    ->size('lg'),
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->weight('bold')
                    ->sortable(),
                Tables\Columns\TextColumn::make('category')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'wellness' => 'success',
                        'culture' => 'warning',
                        'adventure' => 'danger',
                        'nature' => 'info',
                        'food' => 'primary',
                        default => 'gray',
                    })
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('price_usd')
                    ->label('Price')
                    ->money('usd')
                    ->sortable()
                    ->placeholder('Free'),
                Tables\Columns\TextColumn::make('duration_hours')
                    ->label('Duration')
                    ->suffix(' hrs')
                    ->sortable(),
                Tables\Columns\TextColumn::make('location')
                    ->searchable()
                    ->limit(25),
                Tables\Columns\ImageColumn::make('image_url')
                    ->circular()
                    ->size(50),
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean()
                    ->sortable(),
                Tables\Columns\TextColumn::make('sort_order')
                    ->label('Order')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('sort_order', 'asc')
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMicroExperiences::route('/'),
            'create' => Pages\CreateMicroExperience::route('/create'),
            'edit' => Pages\EditMicroExperience::route('/{record}/edit'),
        ];
    }
}
