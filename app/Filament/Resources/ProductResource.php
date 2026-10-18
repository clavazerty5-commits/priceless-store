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
    protected static ?string $navigationLabel = 'المنتجات';
    protected static ?string $modelLabel = 'منتج';
    protected static ?string $pluralModelLabel = 'المنتجات';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('اسم المنتج')
                    ->required(),
                Select::make('category_id')
                    ->relationship('category', 'name')
                    ->label('التصنيف')
                    ->required(),
                TextInput::make('old_price')
                    ->label('السعر القديم (اختياري)')
                    ->numeric(),
                TextInput::make('price')
                    ->label('السعر الجديد')
                    ->required()
                    ->numeric(),
                TextInput::make('stock')
                    ->label('الكمية المتوفرة')
                    ->required()
                    ->numeric(),
                FileUpload::make('image')
                    ->label('صورة المنتج')
                    ->image()
                    ->directory('products')
                    ->disk('public')
                    ->required()
                    ->columnSpanFull(),
                Textarea::make('description')
                    ->label('الوصف')
                    ->required()
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('image')
                    ->label('الصورة'),
                TextColumn::make('name')
                    ->label('الاسم')
                    ->searchable(),
                TextColumn::make('category.name')
                    ->label('التصنيف')
                    ->sortable(),
                TextColumn::make('price')
                    ->label('السعر')
                    ->sortable(),
                TextColumn::make('stock')
                    ->label('الكمية')
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
