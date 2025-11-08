<?php

namespace App\Filament\Resources;

use App\Models\FloatingMemory;
use App\Models\Destination;
use App\Models\Package;
use Filament\Resources\Resource;
use App\Filament\Resources\FloatingMemoryResource\Pages;
use Filament\Tables;
use Filament\Forms;

class FloatingMemoryResource extends Resource
{
    protected static ?string $model = FloatingMemory::class;
    protected static ?string $navigationGroup = 'Package Management';
    protected static ?string $navigationIcon = 'heroicon-o-photo';
    protected static ?int $navigationSort = 5;
    protected static ?string $navigationLabel = 'Floating Memories';
    protected static ?string $modelLabel = 'Memory';
    protected static ?string $pluralModelLabel = 'Floating Memories';

    public static function canViewAny(): bool
    {
    $user = filament()->auth()->user();
    return $user && in_array($user->role, ['admin', 'portal_user']);
    }

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Location & Package')
                    ->schema([
                        Forms\Components\Select::make('destination_id')
                            ->label('Destination')
                            ->options(Destination::where('is_active', true)->pluck('name', 'id')->toArray())
                            ->searchable()
                            ->placeholder('Select destination where photo was taken'),
                        Forms\Components\Select::make('package_id')
                            ->label('Package')
                            ->options(Package::where('is_active', true)->pluck('title', 'id')->toArray())
                            ->searchable()
                            ->placeholder('Select the safari package'),
                    ])->columns(2),

                Forms\Components\Section::make('Memory Details')
                    ->schema([
                        Forms\Components\Select::make('media_type')
                            ->label('Media Type')
                            ->options([
                                'image' => 'Image',
                                'video' => 'Video',
                            ])
                            ->default('image')
                            ->required()
                            ->live(),
                        Forms\Components\FileUpload::make('image_url')
                            ->label('Photo/Video')
                            ->image()
                            ->directory('floating-memories')
                            ->imageResizeMode('contain')
                            ->imageResizeTargetWidth('2000')
                            ->imageResizeTargetHeight('3000')
                            ->maxSize(10240) // 10MB = 10,240 KB
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp', 'video/mp4', 'video/quicktime'])
                            ->helperText('⚠️ Max file size: 10MB (10,240 KB). If your file exceeds this, compress it first using TinyPNG.com or resize it. Images will be automatically resized to 2000px max after upload.')
                            ->visible(fn ($get) => $get('media_type') === 'image')
                            ->required(fn ($get) => $get('media_type') === 'image')
                            ->disk('public')
                            ->visibility('public')
                            ->deletable()
                            ->downloadable()
                            ->previewable()
                            ->columnSpanFull(),
                        Forms\Components\TextInput::make('video_url')
                            ->label('Video URL')
                            ->url()
                            ->placeholder('https://youtube.com/watch?v=... or direct video URL')
                            ->helperText('Enter YouTube URL or direct video link')
                            ->visible(fn ($get) => $get('media_type') === 'video')
                            ->required(fn ($get) => $get('media_type') === 'video')
                            ->columnSpanFull(),
                        Forms\Components\Textarea::make('caption')
                            ->label('Caption')
                            ->maxLength(300)
                            ->rows(3)
                            ->placeholder('Brief caption for this memory...')
                            ->columnSpanFull(),
                        Forms\Components\TextInput::make('traveler_name')
                            ->label('Traveler Name')
                            ->maxLength(100)
                            ->placeholder('e.g., Sarah from Nairobi'),
                        Forms\Components\Toggle::make('is_traveler_of_month')
                            ->label('Traveler of the Month')
                            ->helperText('Feature this traveler prominently'),
                        Forms\Components\Textarea::make('story')
                            ->label('Traveler Story')
                            ->rows(5)
                            ->placeholder('Share the full story behind this memory...')
                            ->helperText('Rich story text - what made this moment special?')
                            ->columnSpanFull(),
                        Forms\Components\DatePicker::make('safari_date')
                            ->label('Safari Date')
                            ->placeholder('When was this photo taken?')
                            ->maxDate(now())
                            ->displayFormat('F j, Y'),
                        Forms\Components\Select::make('slot')
                            ->label('Website Position Slot')
                            ->options([
                                1 => 'Slot 1',
                                2 => 'Slot 2', 
                                3 => 'Slot 3',
                                4 => 'Slot 4',
                                5 => 'Slot 5',
                                6 => 'Slot 6',
                                7 => 'Slot 7',
                                8 => 'Slot 8',
                                9 => 'Slot 9',
                                10 => 'Slot 10',
                            ])
                            ->placeholder('Select position slot for website display')
                            ->helperText('Choose which slot this memory should appear in on the website'),
                            Forms\Components\Toggle::make('is_published')
                                ->label('Published')
                                ->default(true)
                                ->inline(false)
                                ->columnSpanFull(),
                    ])->columns(2),
            ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image_url')
                    ->label('Photo')
                    ->size(80)
                    ->circular(),
                Tables\Columns\TextColumn::make('memory_title')
                    ->label('Memory')
                    ->getStateUsing(fn (FloatingMemory $record) => $record->memory_title)
                    ->searchable(['caption'])
                    ->limit(30),
                Tables\Columns\TextColumn::make('traveler_name')
                    ->label('Traveler')
                    ->searchable()
                    ->limit(25),
                Tables\Columns\TextColumn::make('caption')
                    ->limit(50)
                    ->placeholder('No caption')
                    ->tooltip(function (Tables\Columns\TextColumn $column): ?string {
                        $state = $column->getState();
                        if (!$state || strlen($state) <= 50) {
                            return null;
                        }
                        return $state;
                    }),
                Tables\Columns\TextColumn::make('media_type')
                    ->label('Type')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'video' => 'danger',
                        'image' => 'success',
                        default => 'gray',
                    }),
                Tables\Columns\IconColumn::make('is_traveler_of_month')
                    ->label('Featured')
                    ->boolean(),
                Tables\Columns\TextColumn::make('destination.name')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color('success')
                    ->placeholder('No destination'),
                Tables\Columns\TextColumn::make('package.title')
                    ->searchable()
                    ->sortable()
                    ->limit(25)
                    ->placeholder('No package'),
                Tables\Columns\TextColumn::make('safari_date')
                    ->label('Safari Date')
                    ->date('M j, Y')
                    ->sortable()
                    ->placeholder('Date unknown'),
                Tables\Columns\TextColumn::make('slot')
                    ->label('Slot')
                    ->sortable()
                    ->badge()
                    ->color('primary')
                    ->formatStateUsing(fn (?int $state): string => $state ? "Slot {$state}" : 'No slot')
                    ->placeholder('No slot'),
                Tables\Columns\TextColumn::make('memory_age')
                    ->label('Age')
                    ->getStateUsing(fn (FloatingMemory $record) => $record->memory_age)
                    ->badge()
                    ->color('info')
                    ->placeholder('Unknown'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Added')
                    ->dateTime('M j, Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                    Tables\Columns\ToggleColumn::make('is_published')
                        ->label('Published')
                        ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('destination_id')
                    ->label('Destination')
                    ->relationship('destination', 'name')
                    ->searchable()
                    ->preload(),
                Tables\Filters\SelectFilter::make('package_id')
                    ->label('Package')
                    ->relationship('package', 'title')
                    ->searchable()
                    ->preload(),
                Tables\Filters\SelectFilter::make('slot')
                    ->label('Position Slot')
                    ->options([
                        1 => 'Slot 1',
                        2 => 'Slot 2',
                        3 => 'Slot 3',
                        4 => 'Slot 4',
                        5 => 'Slot 5',
                        6 => 'Slot 6',
                        7 => 'Slot 7',
                        8 => 'Slot 8',
                        9 => 'Slot 9',
                        10 => 'Slot 10',
                    ]),
                Tables\Filters\Filter::make('safari_date')
                    ->form([
                        Forms\Components\DatePicker::make('safari_from')
                            ->label('Safari From'),
                        Forms\Components\DatePicker::make('safari_until')
                            ->label('Safari Until'),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when($data['safari_from'], fn ($query, $date) => 
                                $query->whereDate('safari_date', '>=', $date)
                            )
                            ->when($data['safari_until'], fn ($query, $date) => 
                                $query->whereDate('safari_date', '<=', $date)
                            );
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
            ])
            ->defaultSort('safari_date', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListFloatingMemories::route('/'),
            'create' => Pages\CreateFloatingMemory::route('/create'),
            'view' => Pages\ViewFloatingMemory::route('/{record}'),
            'edit' => Pages\EditFloatingMemory::route('/{record}/edit'),
        ];
    }
}
