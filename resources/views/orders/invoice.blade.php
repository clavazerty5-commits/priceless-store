<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('وصل تسليم / فاتورة طلب') }} #{{ $order->id }} - PRICELESS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            .no-print {
                display: none !important;
            }
            body {
                padding: 0 !important;
                background: white !important;
            }
            .print-border {
                border: 1px solid #e5e7eb !important;
            }
        }
    </style>
</head>
<body class="bg-gray-100 min-h-screen py-8 text-gray-800">
    <div class="max-w-3xl mx-auto bg-white p-8 rounded-lg shadow-sm print-border">
        <!-- Action Buttons (Hidden on Print) -->
        <div class="no-print flex items-center justify-between border-b pb-4 mb-6">
            <a href="{{ url('/') }}" class="text-sm font-medium text-gray-600 hover:text-black flex items-center gap-2">
                &larr; {{ __('العودة للمتجر') }}
            </a>
            <div class="flex items-center gap-3">
                <button onclick="window.print()" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-5 rounded-md text-sm flex items-center gap-2 shadow-sm transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                    {{ __('طباعة الفاتورة') }}
                </button>
            </div>
        </div>

        <!-- Header -->
        <div class="flex items-center justify-between border-b pb-6">
            <div>
                <h1 class="text-2xl font-black text-gray-900 tracking-wider">PRICELESS</h1>
                <p class="text-xs text-gray-500 mt-1">{{ \App\Models\Setting::get('phone', '+216 23 456 789') }}</p>
                <p class="text-xs text-gray-500">{{ \App\Models\Setting::get('address', 'Tunis, Tunisia') }}</p>
            </div>
            <div class="text-end">
                <span class="inline-block px-3 py-1 bg-amber-100 text-amber-800 rounded text-xs font-bold uppercase mb-2">
                    {{ __('وصل تسليم / فاتورة طلب') }}
                </span>
                <p class="text-lg font-bold text-gray-900">#{{ $order->id }}</p>
                <p class="text-xs text-gray-500">{{ $order->created_at->format('d/m/Y - H:i') }}</p>
            </div>
        </div>

        <!-- Customer & Order Meta -->
        <div class="grid grid-cols-2 gap-6 py-6 border-b text-sm">
            <div>
                <h3 class="font-bold text-gray-900 mb-2">{{ __('معلومات العميل') }}:</h3>
                <p class="text-gray-700 font-semibold">{{ $order->name }}</p>
                <p class="text-gray-600 mt-1">{{ __('رقم الهاتف') }}: <span class="font-bold text-gray-900">{{ $order->phone }}</span></p>
                <p class="text-gray-600 mt-1">{{ __('المدينة') }}: {{ $order->city }}</p>
                <p class="text-gray-600 mt-1">{{ __('العنوان بالتفصيل') }}: {{ $order->address }}</p>
            </div>
            <div class="text-end">
                <h3 class="font-bold text-gray-900 mb-2">{{ __('تفاصيل الطلب') }}:</h3>
                <p class="text-gray-600">{{ __('حالة الطلب') }}: <span class="font-bold text-gray-900 uppercase">{{ __($order->status) }}</span></p>
                <p class="text-gray-600 mt-1">{{ __('طريقة الدفع') }}: <span class="font-bold text-green-700">{{ __('الدفع نقداً عند الاستلام (COD)') }}</span></p>
            </div>
        </div>

        <!-- Items Table -->
        <div class="py-6">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b text-gray-500 font-semibold text-xs uppercase">
                        <th class="text-start pb-3">{{ __('المنتج') }}</th>
                        <th class="text-center pb-3">{{ __('الكمية') }}</th>
                        <th class="text-end pb-3">{{ __('السعر') }}</th>
                        <th class="text-end pb-3">{{ __('المجموع الفرعي') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($order->items as $item)
                        <tr>
                            <td class="py-3 font-medium text-gray-900">
                                {{ $item->product->name ?? __('منتج') }}
                            </td>
                            <td class="py-3 text-center text-gray-700 font-semibold">
                                {{ $item->quantity }}
                            </td>
                            <td class="py-3 text-end text-gray-700">
                                {{ number_format($item->price, 2) }} {{ __('د.ت') }}
                            </td>
                            <td class="py-3 text-end font-bold text-gray-900">
                                {{ number_format($item->price * $item->quantity, 2) }} {{ __('د.ت') }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Totals -->
        <div class="border-t pt-4 space-y-2 text-sm">
            <div class="flex justify-between text-gray-600">
                <span>{{ __('المجموع الفرعي') }}</span>
                <span>{{ number_format($order->total - $order->delivery_fee, 2) }} {{ __('د.ت') }}</span>
            </div>
            <div class="flex justify-between text-gray-600">
                <span>{{ __('سعر التوصيل') }}</span>
                <span>{{ number_format($order->delivery_fee, 2) }} {{ __('د.ت') }}</span>
            </div>
            <div class="flex justify-between text-base font-black text-gray-900 border-t pt-2">
                <span>{{ __('المجموع الكلي') }}</span>
                <span class="text-indigo-600 text-lg">{{ number_format($order->total, 2) }} {{ __('د.ت') }}</span>
            </div>
        </div>

        <!-- Footer / Delivery Notes -->
        <div class="mt-8 pt-6 border-t text-center text-xs text-gray-500">
            <p class="font-bold text-gray-700 mb-1">PRICELESS STORE - {{ __('توصيل لكافة مدن تونس') }}</p>
            <p>{{ __('شكراً لثقتك بنا. سنتصل بك قريباً لتأكيد موعد التسليم.') }}</p>
        </div>
    </div>
</body>
</html>
