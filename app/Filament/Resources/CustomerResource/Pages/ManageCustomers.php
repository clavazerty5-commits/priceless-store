<?php

namespace App\Filament\Resources\CustomerResource\Pages;

use App\Filament\Resources\CustomerResource;
use Filament\Resources\Pages\ManageRecords;
use Filament\Actions\CreateAction;

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
}
