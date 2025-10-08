<?php

namespace App\Filament\Resources\PackageResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Set;

class ItinerariesRelationManager extends RelationManager
{
    protected static string $relationship = 'itineraries';
    protected static ?string $title = 'Daily Itinerary';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
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
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('title')
            ->columns([
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
                    ->placeholder('None'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('day_number');
    }
}
