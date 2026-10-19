<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Models\Product;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
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

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-cube';
    protected static ?int $navigationSort = 1;

    public static function getNavigationLabel(): string
    {
        return app()->getLocale() === 'fr' ? 'Produits' : 'المنتجات';
    }

    public static function getModelLabel(): string
    {
        return app()->getLocale() === 'fr' ? 'Produit' : 'منتج';
    }

    public static function getPluralModelLabel(): string
    {
        return app()->getLocale() === 'fr' ? 'Produits' : 'المنتجات';
    }

    public static function form(Schema $schema): Schema
    {
        $isFr = app()->getLocale() === 'fr';

        return $schema
            ->components([
                TextInput::make('name')
                    ->label($isFr ? 'Nom du produit' : 'اسم المنتج')
                    ->required(),
                Select::make('category_id')
                    ->relationship('category', 'name')
                    ->label($isFr ? 'Catégorie' : 'القسم')
                    ->required(),
                TextInput::make('old_price')
                    ->label($isFr ? 'Ancien prix (Optionnel)' : 'السعر القديم (قبل التخفيض)')
                    ->numeric(),
                TextInput::make('price')
                    ->label($isFr ? 'Prix actuel' : 'السعر الحالي')
                    ->required()
                    ->numeric(),
                TextInput::make('stock')
                    ->label($isFr ? 'Quantité en stock' : 'الكمية في المخزون')
                    ->required()
                    ->numeric(),
                FileUpload::make('image')
                    ->label($isFr ? 'Image du produit' : 'صورة المنتج')
                    ->image()
                    ->directory('products')
                    ->disk('public')
                    ->required()
                    ->columnSpanFull(),
                Textarea::make('description')
                    ->label($isFr ? 'Description' : 'الوصف')
                    ->required()
                    ->columnSpanFull(),
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
                    ->label($isFr ? 'Produit' : 'المنتج')
                    ->searchable()
                    ->weight('bold'),
                TextColumn::make('category.name')
                    ->label($isFr ? 'Catégorie' : 'القسم')
                    ->sortable(),
                TextColumn::make('price')
                    ->label($isFr ? 'Prix' : 'السعر')
                    ->formatStateUsing(fn ($state) => number_format($state, 2) . ' ' . ($isFr ? 'DT' : 'د.ت'))
                    ->sortable()
                    ->weight('bold'),
                TextColumn::make('stock')
                    ->label($isFr ? 'Stock' : 'المخزون')
                    ->badge()
                    ->color(fn (int $state): string => match (true) {
                        $state <= 3 => 'danger',
                        $state <= 10 => 'warning',
                        default => 'success',
                    })
                    ->formatStateUsing(fn ($state) => $state <= 3 
                        ? ($state . ' ⚠️ ' . ($isFr ? '(Stock Faible)' : '(مخزون منخفض)')) 
                        : $state
                    )
                    ->sortable(),
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
            'index' => Pages\ManageProducts::route('/'),
        ];
    }
}
