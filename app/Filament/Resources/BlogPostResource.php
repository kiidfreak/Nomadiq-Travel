<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BlogPostResource\Pages;
use App\Filament\Resources\BlogPostResource\RelationManagers;
use App\Models\BlogPost;
use App\Models\BlogCategory;
use App\Models\Package;
use App\Models\Destination;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class BlogPostResource extends Resource
{
    protected static ?string $model = BlogPost::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationGroup = 'Blog Management';

    protected static ?int $navigationSort = 11;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Post Content')
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->required()
                            ->maxLength(200)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn (string $context, $state, callable $set) => 
                                $context === 'create' ? $set('slug', Str::slug($state)) : null
                            ),
                        Forms\Components\TextInput::make('slug')
                            ->required()
                            ->maxLength(200)
                            ->unique(BlogPost::class, 'slug', ignoreRecord: true)
                            ->rules(['alpha_dash']),
                        Forms\Components\RichEditor::make('content')
                            ->required()
                            ->columnSpanFull()
                            ->toolbarButtons([
                                'bold', 'italic', 'link', 'bulletList', 'orderedList', 
                                'h2', 'h3', 'blockquote', 'codeBlock'
                            ]),
                        Forms\Components\FileUpload::make('featured_image')
                            ->image()
                            ->directory('blog-images')
                            ->imageResizeMode('cover')
                            ->imageCropAspectRatio('16:9')
                            ->imageResizeTargetWidth('1200')
                            ->imageResizeTargetHeight('675'),
                    ])->columns(2),

                Forms\Components\Section::make('Relationships')
                    ->schema([
                        Forms\Components\Select::make('category_id')
                            ->label('Category')
                            ->relationship('category', 'name')
                            ->searchable()
                            ->preload()
                            ->createOptionForm([
                                Forms\Components\TextInput::make('name')
                                    ->required()
                                    ->maxLength(100),
                                Forms\Components\Textarea::make('description'),
                            ]),
                        Forms\Components\Select::make('package_id')
                            ->label('Related Package')
                            ->relationship('package', 'title')
                            ->searchable()
                            ->preload()
                            ->nullable(),
                        Forms\Components\Select::make('destination_id')
                            ->label('Related Destination')
                            ->relationship('destination', 'name')
                            ->searchable()
                            ->preload()
                            ->nullable(),
                        Forms\Components\Select::make('user_id')
                            ->label('Author')
                            ->relationship('author', 'name')
                            ->required()
                            ->searchable()
                            ->preload()
                            ->default(fn () => Auth::id()),
                    ])->columns(2),

                Forms\Components\Section::make('Publication & SEO')
                    ->schema([
                        Forms\Components\Toggle::make('is_published')
                            ->label('Published')
                            ->default(false)
                            ->live()
                            ->afterStateUpdated(function ($state, callable $set) {
                                if ($state) {
                                    $set('published_at', now());
                                } else {
                                    $set('published_at', null);
                                }
                            }),
                        Forms\Components\DateTimePicker::make('published_at')
                            ->label('Publish Date')
                            ->nullable()
                            ->visible(fn (callable $get) => $get('is_published')),
                        Forms\Components\Textarea::make('meta_description')
                            ->label('Meta Description (SEO)')
                            ->maxLength(160)
                            ->rows(3)
                            ->hint('Max 160 characters for optimal SEO')
                            ->columnSpanFull(),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('featured_image')
                    ->circular()
                    ->size(60),
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->sortable()
                    ->limit(50)
                    ->tooltip(function (Tables\Columns\TextColumn $column): ?string {
                        $state = $column->getState();
                        if (strlen($state) <= 50) {
                            return null;
                        }
                        return $state;
                    }),
                Tables\Columns\TextColumn::make('slug')
                    ->searchable()
                    ->copyable()
                    ->badge()
                    ->color('gray')
                    ->limit(30),
                Tables\Columns\TextColumn::make('category.name')
                    ->label('Category')
                    ->badge()
                    ->color('success')
                    ->sortable(),
                Tables\Columns\TextColumn::make('author.name')
                    ->label('Author')
                    ->sortable(),
                Tables\Columns\ToggleColumn::make('is_published')
                    ->label('Published'),
                Tables\Columns\TextColumn::make('published_at')
                    ->label('Published Date')
                    ->dateTime('M j, Y')
                    ->sortable()
                    ->placeholder('Not published'),
                Tables\Columns\TextColumn::make('package.title')
                    ->label('Package')
                    ->limit(30)
                    ->placeholder('No package')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('destination.name')
                    ->label('Destination')
                    ->limit(30)
                    ->placeholder('No destination')
                    ->toggleable(isToggledHiddenByDefault: true),
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
                Tables\Filters\SelectFilter::make('category_id')
                    ->label('Category')
                    ->relationship('category', 'name')
                    ->searchable()
                    ->preload(),
                Tables\Filters\TernaryFilter::make('is_published')
                    ->label('Publication Status')
                    ->boolean()
                    ->trueLabel('Published only')
                    ->falseLabel('Drafts only')
                    ->native(false),
                Tables\Filters\SelectFilter::make('user_id')
                    ->label('Author')
                    ->relationship('author', 'name')
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
            ->defaultSort('created_at', 'desc');
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
            'index' => Pages\ListBlogPosts::route('/'),
            'create' => Pages\CreateBlogPost::route('/create'),
            'view' => Pages\ViewBlogPost::route('/{record}'),
            'edit' => Pages\EditBlogPost::route('/{record}/edit'),
        ];
    }
}
