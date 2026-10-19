<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class OrdersChart extends ChartWidget
{
    protected static ?int $sort = 2;

    public function getHeading(): string
    {
        return app()->getLocale() === 'fr' ? 'Évolution des Ventes (7 Derniers Jours)' : 'حركة المبيعات (آخر 7 أيام)';
    }

    protected function getData(): array
    {
        $days = collect(range(6, 0))->map(fn ($i) => Carbon::today()->subDays($i));
        
        $labels = [];
        $data = [];

        foreach ($days as $day) {
            $labels[] = $day->format('d/m');
            $data[] = (float) Order::whereDate('created_at', $day)->sum('total');
        }

        return [
            'datasets' => [
                [
                    'label' => app()->getLocale() === 'fr' ? 'Ventes (DT)' : 'المبيعات (د.ت)',
                    'data' => $data,
                    'borderColor' => '#f59e0b',
                    'fill' => 'start',
                    'backgroundColor' => 'rgba(245, 158, 11, 0.15)',
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
