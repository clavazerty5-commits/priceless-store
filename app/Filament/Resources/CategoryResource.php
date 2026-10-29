<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CategoryResource\Pages;
use App\Models\Category;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\BulkActionGroup;
use Illuminate\Support\Str;

class CategoryResource extends Resource
{
    protected static ?string $model = Category::class;
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-tag';
    protected static ?int $navigationSort = 3;

    public static function getNavigationLabel(): string
    {
        return app()->getLocale() === 'fr' ? 'Catégories' : 'الأقسام';
    }

    public static function getModelLabel(): string
    {
        return app()->getLocale() === 'fr' ? 'Catégorie' : 'قسم';
    }

    public static function getPluralModelLabel(): string
    {
        return app()->getLocale() === 'fr' ? 'Catégories' : 'الأقسام';
    }

    public static function form(Schema $schema): Schema
    {
        $isFr = app()->getLocale() === 'fr';

        return $schema
            ->components([
                TextInput::make('name')
                    ->label($isFr ? 'Nom de la catégorie' : 'اسم القسم')
                    ->required()
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn (string $operation, $state, $set) => $operation === 'create' ? $set('slug', Str::slug($state)) : null),
                TextInput::make('slug')
                    ->label($isFr ? 'Identifiant URL (Slug)' : 'المعرف اللطيف (Slug)')
                    ->required()
                    ->unique(ignoreRecord: true),
                FileUpload::make('image')
                    ->label($isFr ? 'Image de la catégorie' : 'صورة القسم')
                    ->image()
                    ->imageEditor()
                    ->imageCropAspectRatio('1:1')
                    ->directory('categories')
                    ->disk('public')
                    ->columnSpanFull()
                    ->helperText($isFr ? 'Format carré 1:1 recommandé.' : 'يُفضل تنسيق مربع 1:1 للأقسام.'),
            ]);
    }

    public static function table(Table $table): Table
    {
        $isFr = app()->getLocale() === 'fr';

        return $table
            ->columns([
                ImageColumn::make('image')
                    ->label($isFr ? 'Image' : 'الصورة'),
                TextColumn::make('name')
                    ->label($isFr ? 'Catégorie' : 'اسم القسم')
                    ->searchable()
                    ->weight('bold'),
                TextColumn::make('slug')
                    ->label($isFr ? 'Slug' : 'المعرف اللطيف')
                    ->searchable(),
                TextColumn::make('created_at')
                    ->label($isFr ? 'Date de création' : 'تاريخ الإنشاء')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageCategories::route('/'),
        ];
    }
}
