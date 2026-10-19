<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CouponResource\Pages;
use App\Models\Coupon;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\BulkActionGroup;

class CouponResource extends Resource
{
    protected static ?string $model = Coupon::class;
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-ticket';
    protected static ?int $navigationSort = 5;

    public static function getNavigationLabel(): string
    {
        return app()->getLocale() === 'fr' ? 'Codes Promo' : 'كوبونات الخصم';
    }

    public static function getModelLabel(): string
    {
        return app()->getLocale() === 'fr' ? 'Code Promo' : 'كوبون خصم';
    }

    public static function getPluralModelLabel(): string
    {
        return app()->getLocale() === 'fr' ? 'Codes Promo' : 'الكوبونات';
    }

    public static function form(Schema $schema): Schema
    {
        $isFr = app()->getLocale() === 'fr';

        return $schema
            ->components([
                TextInput::make('code')
                    ->label($isFr ? 'Code promo' : 'كود الكوبون')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->placeholder('PRICELESS10'),
                Select::make('type')
                    ->label($isFr ? 'Type de réduction' : 'نوع الخصم')
                    ->options([
                        'fixed' => $isFr ? 'Montant fixe (DT)' : 'مبلغ ثابت (د.ت)',
                        'percent' => $isFr ? 'Pourcentage (%)' : 'نسبة مئوية (%)',
                    ])
                    ->default('fixed')
                    ->required(),
                TextInput::make('value')
                    ->label($isFr ? 'Valeur de la réduction' : 'قيمة الخصم')
                    ->numeric()
                    ->required()
                    ->placeholder('10'),
                TextInput::make('min_order_amount')
                    ->label($isFr ? 'Montant minimum d\'achat (DT)' : 'الحد الأدنى للطلب (د.ت)')
                    ->numeric()
                    ->placeholder($isFr ? 'Optionnel - ex: 50' : 'اختياري - مثلاً 50'),
                DatePicker::make('expires_at')
                    ->label($isFr ? 'Date d\'expiration' : 'تاريخ انتهاء الصلاحية')
                    ->placeholder($isFr ? 'Optionnel' : 'اختياري'),
                Toggle::make('is_active')
                    ->label($isFr ? 'Activer le coupon' : 'تفعيل الكوبون')
                    ->default(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        $isFr = app()->getLocale() === 'fr';

        return $table
            ->columns([
                TextColumn::make('code')
                    ->label($isFr ? 'Code' : 'الكود')
                    ->searchable()
                    ->weight('bold')
                    ->copyable(),
                TextColumn::make('type')
                    ->label($isFr ? 'Type' : 'النوع')
                    ->formatStateUsing(fn ($state) => $state === 'percent' 
                        ? ($isFr ? 'Pourcentage (%)' : 'نسبة مئوية (%)') 
                        : ($isFr ? 'Montant fixe (DT)' : 'مبلغ ثابت (د.ت)')),
                TextColumn::make('value')
                    ->label($isFr ? 'Valeur' : 'القيمة')
                    ->formatStateUsing(fn ($record) => $record->type === 'percent' 
                        ? "{$record->value}%" 
                        : "{$record->value} " . ($isFr ? 'DT' : 'د.ت')),
                TextColumn::make('min_order_amount')
                    ->label($isFr ? 'Min. Commande' : 'الحد الأدنى للطلب')
                    ->formatStateUsing(fn ($state) => $state ? "{$state} " . ($isFr ? 'DT' : 'د.ت') : '-'),
                TextColumn::make('expires_at')
                    ->label($isFr ? 'Expiration' : 'تاريخ الانتهاء')
                    ->date()
                    ->placeholder($isFr ? 'Permanent' : 'غير محدد'),
                IconColumn::make('is_active')
                    ->label($isFr ? 'Actif' : 'مفعّل')
                    ->boolean(),
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
            'index' => Pages\ManageCoupons::route('/'),
        ];
    }
}
