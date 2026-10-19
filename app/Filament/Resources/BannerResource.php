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
    protected static ?int $navigationSort = 4;

    public static function getNavigationLabel(): string
    {
        return app()->getLocale() === 'fr' ? 'Bannières' : 'البانرات الإعلانية';
    }

    public static function getModelLabel(): string
    {
        return app()->getLocale() === 'fr' ? 'Bannière' : 'بانر';
    }

    public static function getPluralModelLabel(): string
    {
        return app()->getLocale() === 'fr' ? 'Bannières' : 'البانرات';
    }

    public static function form(Schema $schema): Schema
    {
        $isFr = app()->getLocale() === 'fr';

        return $schema
            ->components([
                TextInput::make('title')
                    ->label($isFr ? 'Titre de la bannière' : 'عنوان البانر الرئيسي')
                    ->required()
                    ->placeholder('SUPER SALE'),
                TextInput::make('badge')
                    ->label($isFr ? 'Badge promotionnel' : 'الشارة الترويجية')
                    ->default($isFr ? 'Offre Spéciale' : 'عرض خاص')
                    ->placeholder($isFr ? 'Offre Spéciale' : 'عرض خاص'),
                Textarea::make('subtitle')
                    ->label($isFr ? 'Sous-titre / Description' : 'الوصف أو النص الفرعي')
                    ->placeholder($isFr ? 'Jusqu\'à 50% de réduction...' : 'تخفيضات تصل إلى 50% على تشكيلة واسعة...')
                    ->columnSpanFull(),
                FileUpload::make('image')
                    ->label($isFr ? 'Image de la bannière' : 'صورة البانر')
                    ->image()
                    ->directory('banners')
                    ->disk('public')
                    ->columnSpanFull(),
                TextInput::make('link')
                    ->label($isFr ? 'Lien de redirection' : 'رابط التوجيه')
                    ->default('#')
                    ->placeholder('/products ou #'),
                TextInput::make('order')
                    ->label($isFr ? 'Ordre d\'affichage' : 'ترتيب العرض')
                    ->numeric()
                    ->default(0),
                Toggle::make('is_active')
                    ->label($isFr ? 'Activer la bannière' : 'تفعيل البانر')
                    ->default(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        $isFr = app()->getLocale() === 'fr';

        return $table
            ->columns([
                ImageColumn::make('image')
                    ->label($isFr ? 'Image' : 'الصورة'),
                TextColumn::make('title')
                    ->label($isFr ? 'Titre' : 'العنوان')
                    ->searchable()
                    ->weight('bold'),
                TextColumn::make('badge')
                    ->label($isFr ? 'Badge' : 'الشارة'),
                TextColumn::make('order')
                    ->label($isFr ? 'Ordre' : 'الترتيب')
                    ->sortable(),
                IconColumn::make('is_active')
                    ->label($isFr ? 'Actif' : 'مفعّل')
                    ->boolean(),
                TextColumn::make('created_at')
                    ->label($isFr ? 'Date de création' : 'تاريخ الإنشاء')
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
