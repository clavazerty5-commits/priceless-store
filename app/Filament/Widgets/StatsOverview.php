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
        $totalSales = Order::whereIn('status', ['delivered', 'processing', 'shipped'])->sum('total');
        $ordersCount = Order::count();
        $productsCount = Product::count();
        $customersCount = User::where('email', '!=', 'admin@admin.com')->count();

        return [
            Stat::make('إجمالي المبيعات', number_format($totalSales, 2) . ' د.ت')
                ->description('الطلبات المؤكدة والمسلمة')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success'),
            Stat::make('إجمالي الطلبات', $ordersCount)
                ->description('كافة طلبات المتجر')
                ->descriptionIcon('heroicon-m-shopping-bag')
                ->color('primary'),
            Stat::make('المنتجات في المتجر', $productsCount)
                ->description('جاهزة للبيع والتسليم')
                ->descriptionIcon('heroicon-m-archive-box')
                ->color('warning'),
            Stat::make('العملاء المسجلين', $customersCount)
                ->description('من حسابات المتجر')
                ->descriptionIcon('heroicon-m-user-group')
                ->color('info'),
        ];
    }
}
