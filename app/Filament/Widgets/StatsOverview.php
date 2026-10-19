<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $isFr = app()->getLocale() === 'fr';

        $totalSales = Order::whereIn('status', ['delivered', 'processing', 'shipped'])->sum('total');
        $ordersCount = Order::count();
        $productsCount = Product::count();
        $customersCount = User::where('email', '!=', 'admin@admin.com')->count();

        $currency = $isFr ? ' DT' : ' د.ت';

        return [
            Stat::make(
                $isFr ? 'Ventes Totales' : 'إجمالي المبيعات', 
                number_format($totalSales, 2) . $currency
            )
                ->description($isFr ? 'Commandes confirmées' : 'الطلبات المؤكدة والمسلمة')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success'),

            Stat::make(
                $isFr ? 'Total Commandes' : 'إجمالي الطلبات', 
                $ordersCount
            )
                ->description($isFr ? 'Toutes les commandes' : 'كافة طلبات المتجر')
                ->descriptionIcon('heroicon-m-shopping-bag')
                ->color('primary'),

            Stat::make(
                $isFr ? 'Produits en Boutique' : 'المنتجات في المتجر', 
                $productsCount
            )
                ->description($isFr ? 'Prêts à la vente' : 'جاهزة للبيع والتسليم')
                ->descriptionIcon('heroicon-m-archive-box')
                ->color('warning'),

            Stat::make(
                $isFr ? 'Clients Enregistrés' : 'العملاء المسجلين', 
                $customersCount
            )
                ->description($isFr ? 'Comptes clients' : 'من حسابات المتجر')
                ->descriptionIcon('heroicon-m-user-group')
                ->color('info'),
        ];
    }
}
