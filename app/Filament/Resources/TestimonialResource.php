<?php

namespace App\Filament\Resources;

use App\Models\Testimonial;
use App\Models\Package;
use Filament\Resources\Resource;
use App\Filament\Resources\TestimonialResource\Pages;
use Filament\Tables;
use Filament\Forms;

class TestimonialResource extends Resource
{
    protected static ?string $model = Testimonial::class;
    protected static ?string $navigationGroup = 'Customer Management';
    protected static ?string $navigationIcon = 'heroicon-o-star';
    protected static ?int $navigationSort = 4;
    protected static ?string $navigationLabel = 'Testimonials';
    protected static ?string $modelLabel = 'Testimonial';
    protected static ?string $pluralModelLabel = 'Testimonials';

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Customer Information')
                    ->schema([
                        Forms\Components\TextInput::make('customer_name')
                            ->label('Customer Name')
                            ->required()
                            ->maxLength(100)
                            ->placeholder('Enter customer full name'),
                        Forms\Components\Select::make('package_id')
                            ->label('Package')
                            ->options(Package::where('is_active', true)->pluck('title', 'id')->toArray())
                            ->searchable()
                            ->placeholder('Select the package this review is for')
                            ->nullable(),
                    ])->columns(2),

                Forms\Components\Section::make('Review Details')
                    ->schema([
                        Forms\Components\Select::make('rating')
                            ->label('Rating')
                            ->options([
                                1 => '⭐ 1 Star - Poor',
                                2 => '⭐⭐ 2 Stars - Fair', 
                                3 => '⭐⭐⭐ 3 Stars - Good',
                                4 => '⭐⭐⭐⭐ 4 Stars - Very Good',
                                5 => '⭐⭐⭐⭐⭐ 5 Stars - Excellent',
                            ])
                            ->required()
                            ->native(false)
                            ->prefixIcon('heroicon-m-star')
                            ->suffixIcon('heroicon-m-chevron-down')
                            ->placeholder('Select star rating'),
                        Forms\Components\Textarea::make('review_text')
                            ->label('Review Text')
                            ->rows(4)
                            ->placeholder('Customer review and feedback...')
                            ->columnSpanFull(),
                        Forms\Components\Toggle::make('is_published')
                            ->label('Published')
                            ->helperText('Toggle to show/hide this testimonial on the website')
                            ->default(true),
                    ])->columns(2),
            ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('customer_name')
                    ->label('Customer')
                    ->searchable()
                    ->sortable()
                    ->weight('medium'),
                Tables\Columns\TextColumn::make('package.title')
                    ->label('Package')
                    ->searchable()
                    ->sortable()
                    ->limit(25)
                    ->placeholder('General Review')
                    ->badge()
                    ->color('info'),
                Tables\Columns\TextColumn::make('star_rating')
                    ->label('Rating')
                    ->getStateUsing(fn (Testimonial $record) => $record->star_rating)
                    ->badge()
                    ->color(fn (Testimonial $record) => $record->rating_color),
                Tables\Columns\TextColumn::make('rating')
                    ->label('Score')
                    ->sortable()
                    ->alignCenter()
                    ->badge()
                    ->color(fn (Testimonial $record) => $record->rating_color),
                Tables\Columns\TextColumn::make('review_excerpt')
                    ->label('Review')
                    ->getStateUsing(fn (Testimonial $record) => $record->review_excerpt)
                    ->wrap()
                    ->limit(60),
                Tables\Columns\ToggleColumn::make('is_published')
                    ->label('Published')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Submitted')
                    ->dateTime('M j, Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('rating')
                    ->options([
                        1 => '1 Star',
                        2 => '2 Stars',
                        3 => '3 Stars',
                        4 => '4 Stars',
                        5 => '5 Stars',
                    ]),
                Tables\Filters\SelectFilter::make('package_id')
                    ->label('Package')
                    ->relationship('package', 'title')
                    ->searchable()
                    ->preload(),
                Tables\Filters\TernaryFilter::make('is_published')
                    ->label('Published Status')
                    ->trueLabel('Published only')
                    ->falseLabel('Unpublished only')
                    ->native(false),
                Tables\Filters\Filter::make('high_rated')
                    ->label('High Rated (4-5 Stars)')
                    ->query(fn ($query) => $query->where('rating', '>=', 4))
                    ->toggle(),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\Action::make('toggle_published')
                        ->label(fn (Testimonial $record) => $record->is_published ? 'Unpublish' : 'Publish')
                        ->icon(fn (Testimonial $record) => $record->is_published ? 'heroicon-o-eye-slash' : 'heroicon-o-eye')
                        ->color(fn (Testimonial $record) => $record->is_published ? 'warning' : 'success')
                        ->action(fn (Testimonial $record) => $record->is_published ? $record->unpublish() : $record->publish())
                        ->requiresConfirmation(),
                    Tables\Actions\DeleteAction::make(),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\BulkAction::make('publish')
                        ->label('Publish Selected')
                        ->icon('heroicon-o-eye')
                        ->color('success')
                        ->action(fn ($records) => $records->each->publish())
                        ->requiresConfirmation(),
                    Tables\Actions\BulkAction::make('unpublish')
                        ->label('Unpublish Selected')
                        ->icon('heroicon-o-eye-slash')
                        ->color('warning')
                        ->action(fn ($records) => $records->each->unpublish())
                        ->requiresConfirmation(),
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTestimonials::route('/'),
            'create' => Pages\CreateTestimonial::route('/create'),
            'view' => Pages\ViewTestimonial::route('/{record}'),
            'edit' => Pages\EditTestimonial::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('is_published', true)->count();
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'success';
    }
}
