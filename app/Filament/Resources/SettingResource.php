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
    protected static ?string $navigationLabel = 'إعدادات المتجر';
    protected static ?string $modelLabel = 'إعداد';
    protected static ?string $pluralModelLabel = 'الإعدادات';
    protected static ?int $navigationSort = 7;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('key')
                    ->label('اسم الإعداد / المفتاح')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->placeholder('مثال: delivery_fee أو phone'),
                TextInput::make('value')
                    ->label('القيمة')
                    ->required()
                    ->placeholder('أدخل قيمة الإعداد'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('key')
                    ->label('الإعداد')
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'store_name' => 'اسم المتجر',
                        'phone' => 'رقم الهاتف',
                        'email' => 'البريد الإلكتروني',
                        'address' => 'العنوان',
                        'delivery_fee' => 'سعر التوصيل (د.ت)',
                        'free_delivery_threshold' => 'قيمة الطلب للشحن المجاني',
                        'facebook_url' => 'رابط صفحة الفايسبوك',
                        'facebook_pixel_id' => 'معرّف بيكسل فايسبوك (Pixel ID)',
                        default => $state,
                    })
                    ->weight('bold')
                    ->searchable(),
                TextColumn::make('value')
                    ->label('القيمة الحالية')
                    ->searchable(),
                TextColumn::make('updated_at')
                    ->label('آخر تحديث')
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
