<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Tables;

class UserResource extends Resource
{
    protected static ?string $model = User::class;
    protected static ?string $navigationIcon = 'heroicon-o-user';

    public static function canViewAny(): bool
    {
    $user = filament()->auth()->user();
    return $user && $user->role === 'admin';
    }

    
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')->required(),
                Forms\Components\TextInput::make('email')->email()->required(),
                Forms\Components\TextInput::make('password')
                    ->password()
                    ->required()
                    ->minLength(6)
                    ->visibleOn('create'),
                Forms\Components\TextInput::make('password_confirmation')
                    ->password()
                    ->required()
                    ->minLength(6)
                    ->visibleOn('create')
                    ->same('password')
                    ->label('Confirm Password')
                    ->dehydrated(false),
                Forms\Components\Select::make('role')
                    ->options([
                        'admin' => 'Admin',
                        'blogger' => 'Blogger',
                        'portal_user' => 'Portal User',
                    ])
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\TextColumn::make('email'),
                Tables\Columns\TextColumn::make('role'),
            ])
            ->filters([
                // Add filters if needed
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                ])
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
