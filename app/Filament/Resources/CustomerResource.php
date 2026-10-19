<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CustomerResource\Pages;
use App\Models\User;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\BulkActionGroup;
use Illuminate\Database\Eloquent\Builder;

class CustomerResource extends Resource
{
    protected static ?string $model = User::class;
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-users';
    protected static ?int $navigationSort = 6;

    public static function getNavigationLabel(): string
    {
        return app()->getLocale() === 'fr' ? 'Clients' : 'العملاء';
    }

    public static function getModelLabel(): string
    {
        return app()->getLocale() === 'fr' ? 'Client' : 'عميل';
    }

    public static function getPluralModelLabel(): string
    {
        return app()->getLocale() === 'fr' ? 'Clients' : 'العملاء';
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('email', '!=', 'admin@admin.com');
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([]);
    }

    public static function table(Table $table): Table
    {
        $isFr = app()->getLocale() === 'fr';

        return $table
            ->columns([
                TextColumn::make('name')
                    ->label($isFr ? 'Nom du client' : 'اسم العميل')
                    ->searchable()
                    ->weight('bold'),
                TextColumn::make('email')
                    ->label($isFr ? 'Email' : 'البريد الإلكتروني')
                    ->searchable()
                    ->copyable(),
                TextColumn::make('orders_count')
                    ->label($isFr ? 'Nombre de commandes' : 'عدد الطلبات')
                    ->counts('orders')
                    ->badge()
                    ->color('info'),
                TextColumn::make('created_at')
                    ->label($isFr ? 'Date d\'inscription' : 'تاريخ التسجيل')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->recordActions([
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
            'index' => Pages\ManageCustomers::route('/'),
        ];
    }
}
