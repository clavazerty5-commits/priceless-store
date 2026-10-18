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
        $totalSales = Order::where('status', 'completed')->orWhere('status', 'delivered')->sum('total_price');
        $ordersCount = Order::count();
        $productsCount = Product::count();
        $customersCount = User::where('email', '!=', 'admin@admin.com')->count();

        return [
            Stat::make('إجمالي المبيعات', number_format($totalSales, 2) . ' د.ت')
                ->description('المبيعات المحققة')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success'),
            Stat::make('إجمالي الطلبات', $ordersCount)
                ->description('جميع طلبات العملاء')
                ->descriptionIcon('heroicon-m-shopping-bag')
                ->color('primary'),
            Stat::make('المنتجات في المتجر', $productsCount)
                ->description('إجمالي المنتجات المتاحة')
                ->descriptionIcon('heroicon-m-archive-box')
                ->color('warning'),
            Stat::make('العملاء المسجلين', $customersCount)
                ->description('عدد الزبائن في المتجر')
                ->descriptionIcon('heroicon-m-user-group')
                ->color('info'),
        ];
    }
}
