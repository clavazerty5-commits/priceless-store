<?php

namespace App\Filament\Resources\CustomerResource\Pages;

use App\Filament\Resources\CustomerResource;
use Filament\Resources\Pages\ManageRecords;
use Filament\Actions\CreateAction;
use Filament\Resources\Components\Tab;
use Illuminate\Database\Eloquent\Builder;

class ManageCustomers extends ManageRecords
{
    protected static string $resource = CustomerResource::class;

    protected function getHeaderActions(): array
    {
        $isFr = app()->getLocale() === 'fr';

        return [
            CreateAction::make()
                ->label($isFr ? 'Ajouter un utilisateur' : 'إضافة حساب جديد')
                ->icon('heroicon-o-user-plus'),
        ];
    }

    public function getTabs(): array
    {
        $isFr = app()->getLocale() === 'fr';

        return [
            'customers' => Tab::make($isFr ? 'العملاء (Clients)' : 'العملاء')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('is_admin', false))
                ->icon('heroicon-o-users'),
            'admins' => Tab::make($isFr ? 'المدراء (Admins)' : 'المدراء')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('is_admin', true))
                ->icon('heroicon-o-shield-check'),
            'all' => Tab::make($isFr ? 'جميع الحسابات (Tous)' : 'جميع الحسابات')
                ->icon('heroicon-o-user-group'),
        ];
    }
}
