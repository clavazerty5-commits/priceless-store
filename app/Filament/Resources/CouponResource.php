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
    protected static ?string $navigationLabel = 'كوبونات الخصم';
    protected static ?string $modelLabel = 'كوبون خصم';
    protected static ?string $pluralModelLabel = 'الكوبونات';
    protected static ?int $navigationSort = 5;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('code')
                    ->label('كود الكوبون')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->placeholder('مثال: PRICELESS10'),
                Select::make('type')
                    ->label('نوع الخصم')
                    ->options([
                        'fixed' => 'مبلغ ثابت (د.ت)',
                        'percent' => 'نسبة مئوية (%)',
                    ])
                    ->default('fixed')
                    ->required(),
                TextInput::make('value')
                    ->label('قيمة الخصم')
                    ->numeric()
                    ->required()
                    ->placeholder('10'),
                TextInput::make('min_order_amount')
                    ->label('الحد الأدنى للطلب (د.ت)')
                    ->numeric()
                    ->placeholder('اختياري - مثلاً 50'),
                DatePicker::make('expires_at')
                    ->label('تاريخ الانتهاء')
                    ->placeholder('اختياري'),
                Toggle::make('is_active')
                    ->label('تفعيل الكوبون')
                    ->default(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('code')
                    ->label('الكود')
                    ->searchable()
                    ->weight('bold')
                    ->copyable(),
                TextColumn::make('type')
                    ->label('النوع')
                    ->formatStateUsing(fn ($state) => $state === 'percent' ? 'نسبة مئوية (%)' : 'مبلغ ثابت (د.ت)'),
                TextColumn::make('value')
                    ->label('القيمة')
                    ->formatStateUsing(fn ($record) => $record->type === 'percent' ? "{$record->value}%" : "{$record->value} د.ت"),
                TextColumn::make('min_order_amount')
                    ->label('الحد الأدنى')
                    ->formatStateUsing(fn ($state) => $state ? "{$state} د.ت" : '-'),
                TextColumn::make('expires_at')
                    ->label('تاريخ الانتهاء')
                    ->date()
                    ->placeholder('غير محدد'),
                IconColumn::make('is_active')
                    ->label('مفعل')
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
