<?php

namespace App\Filament\Resources;

use App\Models\PackageItinerary;
use App\Models\Package;
use Filament\Resources\Resource;
use App\Filament\Resources\PackageItineraryResource\Pages;
use Filament\Tables;
use Filament\Forms;
use Filament\Forms\Set;

class PackageItineraryResource extends Resource
{
    protected static ?string $model = PackageItinerary::class;
    protected static ?string $navigationGroup = 'Package Management';
    protected static ?string $navigationIcon = 'heroicon-o-calendar';
    protected static ?int $navigationSort = 4;
    protected static ?string $navigationLabel = 'Package Itineraries';

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Itinerary Details')
                    ->schema([
                        Forms\Components\Select::make('package_id')
                            ->label('Package')
                            ->options(Package::where('is_active', true)->pluck('title', 'id')->toArray())
                            ->required()
                            ->searchable(),
                        Forms\Components\TextInput::make('day_number')
                            ->required()
                            ->numeric()
                            ->minValue(1)
                            ->maxValue(30),
                        Forms\Components\TextInput::make('title')
                            ->required()
                            ->maxLength(200)
                            ->placeholder('e.g., Arrival in Nairobi'),
                        Forms\Components\RichEditor::make('description')
                            ->required()
                            ->toolbarButtons([
                                'bold', 'italic', 'link', 'bulletList', 'orderedList'
                            ])
                            ->columnSpanFull(),
                    ])->columns(2),

                Forms\Components\Section::make('Accommodation & Services')
                    ->schema([
                        Forms\Components\TextInput::make('accommodation')
                            ->maxLength(200)
                            ->placeholder('e.g., Safari Lodge, Tented Camp'),
                        Forms\Components\CheckboxList::make('meals_included_array')
                            ->label('Meals Included')
                            ->options([
                                'Breakfast' => 'Breakfast',
                                'Lunch' => 'Lunch',
                                'Dinner' => 'Dinner',
                                'Snacks' => 'Snacks',
                            ])
                            ->columns(4)
                            ->afterStateHydrated(function (Forms\Components\CheckboxList $component, $state, $record) {
                                if ($record && $record->meals_included) {
                                    $component->state($record->meals_array);
                                }
                            })
                            ->live()
                            ->afterStateUpdated(function (Forms\Set $set, $state) {
                                $set('meals_included', $state ? implode(', ', $state) : null);
                            })
                            ->dehydrated(false),
                        Forms\Components\Hidden::make('meals_included'),
                        Forms\Components\RichEditor::make('activities')
                            ->label('Activities & Highlights')
                            ->toolbarButtons([
                                'bold', 'italic', 'link', 'bulletList', 'orderedList'
                            ])
                            ->placeholder('Describe the activities, game drives, cultural experiences, etc.')
                            ->columnSpanFull(),
                    ])->columns(2),
            ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('package.title')
                    ->searchable()
                    ->sortable()
                    ->limit(30),
                Tables\Columns\TextColumn::make('day_number')
                    ->label('Day')
                    ->sortable()
                    ->badge()
                    ->color('info'),
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->limit(40),
                Tables\Columns\TextColumn::make('accommodation')
                    ->limit(30)
                    ->placeholder('Not specified'),
                Tables\Columns\TextColumn::make('meals_included')
                    ->label('Meals')
                    ->badge()
                    ->separator(',')
                    ->color('success')
                    ->placeholder('None specified'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('package_id')
                    ->label('Package')
                    ->relationship('package', 'title')
                    ->searchable()
                    ->preload(),
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
            ])
            ->defaultSort('package_id', 'asc')
            ->defaultSort('day_number', 'asc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPackageItineraries::route('/'),
            'create' => Pages\CreatePackageItinerary::route('/create'),
            'view' => Pages\ViewPackageItinerary::route('/{record}'),
            'edit' => Pages\EditPackageItinerary::route('/{record}/edit'),
        ];
    }
}
