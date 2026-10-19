<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BannerResource\Pages;
use App\Models\Banner;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\BulkActionGroup;

class BannerResource extends Resource
{
    protected static ?string $model = Banner::class;
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-photo';
    protected static ?string $navigationLabel = 'البانرات الإعلانية';
    protected static ?string $modelLabel = 'بانر';
    protected static ?string $pluralModelLabel = 'البانرات';
    protected static ?int $navigationSort = 4;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->label('عنوان البانر الرئيسي')
                    ->required()
                    ->placeholder('SUPER SALE'),
                TextInput::make('badge')
                    ->label('الشارة الترويجية')
                    ->default('عرض خاص')
                    ->placeholder('عرض خاص'),
                Textarea::make('subtitle')
                    ->label('الوصف أو النص الفرعي')
                    ->placeholder('تخفيضات تصل إلى 50% على تشكيلة واسعة...')
                    ->columnSpanFull(),
                FileUpload::make('image')
                    ->label('صورة البانر')
                    ->image()
                    ->directory('banners')
                    ->disk('public')
                    ->columnSpanFull(),
                TextInput::make('link')
                    ->label('رابط التوجيه')
                    ->default('#')
                    ->placeholder('/products أو #'),
                TextInput::make('order')
                    ->label('ترتيب العرض')
                    ->numeric()
                    ->default(0),
                Toggle::make('is_active')
                    ->label('تفعيل البانر')
                    ->default(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('image')
                    ->label('الصورة'),
                TextColumn::make('title')
                    ->label('العنوان')
                    ->searchable()
                    ->weight('bold'),
                TextColumn::make('badge')
                    ->label('الشارة'),
                TextColumn::make('order')
                    ->label('الترتيب')
                    ->sortable(),
                IconColumn::make('is_active')
                    ->label('مفعّل')
                    ->boolean(),
                TextColumn::make('created_at')
                    ->label('تاريخ الإنشاء')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('order', 'asc')
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
            'index' => Pages\ManageBanners::route('/'),
        ];
    }
}
