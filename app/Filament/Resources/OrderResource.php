<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Models\Order;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\SelectColumn;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\BulkActionGroup;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-shopping-bag';
    protected static ?int $navigationSort = 2;

    public static function getNavigationLabel(): string
    {
        return app()->getLocale() === 'fr' ? 'Commandes' : 'الطلبات';
    }

    public static function getModelLabel(): string
    {
        return app()->getLocale() === 'fr' ? 'Commande' : 'طلب';
    }

    public static function getPluralModelLabel(): string
    {
        return app()->getLocale() === 'fr' ? 'Commandes' : 'الطلبات';
    }

    public static function form(Schema $schema): Schema
    {
        $isFr = app()->getLocale() === 'fr';

        return $schema
            ->components([
                Select::make('status')
                    ->label($isFr ? 'Statut de la commande' : 'حالة الطلب')
                    ->options([
                        'pending' => $isFr ? 'En attente' : 'قيد الانتظار',
                        'processing' => $isFr ? 'En cours' : 'قيد التجهيز',
                        'shipped' => $isFr ? 'Expédié' : 'تم الشحن',
                        'delivered' => $isFr ? 'Livré' : 'تم التوصيل',
                        'cancelled' => $isFr ? 'Annulé' : 'ملغي',
                    ])
                    ->required(),
                TextInput::make('name')
                    ->label($isFr ? 'Nom du client' : 'اسم العميل')
                    ->required(),
                TextInput::make('phone')
                    ->label($isFr ? 'Téléphone' : 'الهاتف')
                    ->required(),
                TextInput::make('city')
                    ->label($isFr ? 'Ville' : 'المدينة')
                    ->required(),
                Textarea::make('address')
                    ->label($isFr ? 'Adresse' : 'العنوان')
                    ->required()
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        $isFr = app()->getLocale() === 'fr';

        return $table
            ->columns([
                TextColumn::make('id')
                    ->label($isFr ? 'N° Commande' : 'رقم الطلب')
                    ->sortable()
                    ->weight('bold'),
                TextColumn::make('name')
                    ->label($isFr ? 'Client' : 'العميل')
                    ->searchable()
                    ->description(fn (Order $record) => $record->phone),
                TextColumn::make('city')
                    ->label($isFr ? 'Ville' : 'المدينة')
                    ->searchable(),
                TextColumn::make('total')
                    ->label($isFr ? 'Total' : 'الإجمالي')
                    ->formatStateUsing(fn ($state) => number_format($state, 2) . ' ' . ($isFr ? 'DT' : 'د.ت'))
                    ->sortable()
                    ->weight('bold'),
                SelectColumn::make('status')
                    ->label($isFr ? 'Statut' : 'الحالة')
                    ->options([
                        'pending' => $isFr ? 'En attente' : 'قيد الانتظار',
                        'processing' => $isFr ? 'En cours' : 'قيد التجهيز',
                        'shipped' => $isFr ? 'Expédié' : 'تم الشحن',
                        'delivered' => $isFr ? 'Livré' : 'تم التوصيل',
                        'cancelled' => $isFr ? 'Annulé' : 'ملغي',
                    ])
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label($isFr ? 'Date' : 'تاريخ الطلب')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->recordActions([
                Action::make('print')
                    ->label($isFr ? 'Imprimer Bon' : 'طباعة الوصل')
                    ->icon('heroicon-o-printer')
                    ->color('info')
                    ->url(fn (Order $record) => route('orders.print', $record))
                    ->openUrlInNewTab(),
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                Action::make('export_csv')
                    ->label($isFr ? 'Exporter en Excel/CSV' : 'تصدير إلى Excel / CSV')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('success')
                    ->url(route('orders.export')),
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageOrders::route('/'),
        ];
    }
}
