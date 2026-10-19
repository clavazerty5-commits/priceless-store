<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SettingResource\Pages;
use App\Models\Setting;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;

class SettingResource extends Resource
{
    protected static ?string $model = Setting::class;
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-cog-6-tooth';
    protected static ?int $navigationSort = 7;

    public static function getNavigationLabel(): string
    {
        return app()->getLocale() === 'fr' ? 'Paramètres' : 'إعدادات المتجر';
    }

    public static function getModelLabel(): string
    {
        return app()->getLocale() === 'fr' ? 'Paramètre' : 'إعداد';
    }

    public static function getPluralModelLabel(): string
    {
        return app()->getLocale() === 'fr' ? 'Paramètres' : 'الإعدادات';
    }

    public static function form(Schema $schema): Schema
    {
        $isFr = app()->getLocale() === 'fr';

        return $schema
            ->components([
                TextInput::make('key')
                    ->label($isFr ? 'Clé de configuration' : 'اسم الإعداد / المفتاح')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->placeholder('delivery_fee / phone'),
                TextInput::make('value')
                    ->label($isFr ? 'Valeur' : 'القيمة')
                    ->required()
                    ->placeholder($isFr ? 'Entrez la valeur' : 'أدخل قيمة الإعداد'),
            ]);
    }

    public static function table(Table $table): Table
    {
        $isFr = app()->getLocale() === 'fr';

        return $table
            ->columns([
                TextColumn::make('key')
                    ->label($isFr ? 'Paramètre' : 'الإعداد')
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'store_name' => $isFr ? 'Nom de la boutique' : 'اسم المتجر',
                        'phone' => $isFr ? 'Numéro WhatsApp / Téléphone' : 'رقم الهاتف / واتساب',
                        'email' => $isFr ? 'Email de contact' : 'البريد الإلكتروني',
                        'address' => $isFr ? 'Adresse' : 'العنوان',
                        'delivery_fee' => $isFr ? 'Frais de livraison (DT)' : 'سعر التوصيل (د.ت)',
                        'free_delivery_threshold' => $isFr ? 'Seuil livraison gratuite (DT)' : 'قيمة الطلب للشحن المجاني (د.ت)',
                        'facebook_url' => $isFr ? 'Lien page Facebook' : 'رابط صفحة الفايسبوك',
                        'facebook_pixel_id' => $isFr ? 'Meta / Facebook Pixel ID' : 'معرّف بيكسل فايسبوك (Pixel ID)',
                        default => $state,
                    })
                    ->weight('bold')
                    ->searchable(),
                TextColumn::make('value')
                    ->label($isFr ? 'Valeur actuelle' : 'القيمة الحالية')
                    ->searchable(),
                TextColumn::make('updated_at')
                    ->label($isFr ? 'Dernière mise à jour' : 'آخر تحديث')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageSettings::route('/'),
        ];
    }
}
