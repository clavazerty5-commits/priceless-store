<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('تم استلام طلبك بنجاح!') }} - PRICELESS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="icon" href="/favicon.svg" type="image/svg+xml">
</head>
<body class="bg-gray-50 min-h-screen py-12 px-4 flex flex-col items-center justify-center text-gray-800">
    <div class="max-w-lg w-full bg-white rounded-2xl shadow-lg border border-gray-100 p-8 text-center">
        <!-- Success Icon -->
        <div class="w-20 h-20 bg-green-100 text-green-600 rounded-full flex items-center justify-center mx-auto mb-6 shadow-inner">
            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
        </div>

        <h1 class="text-2xl font-black text-gray-900 mb-2">{{ __('تم استلام طلبك بنجاح!') }}</h1>
        <p class="text-sm text-gray-600 mb-6">{{ __('شكراً لثقتك بنا. سنتصل بك قريباً لتأكيد موعد التسليم.') }}</p>

        <!-- Order Summary Card -->
        <div class="bg-gray-50 rounded-xl p-5 border border-gray-200 text-start space-y-3 mb-6 text-sm">
            <div class="flex justify-between items-center border-b pb-2">
                <span class="text-gray-500 font-medium">{{ __('رقم الطلب') }}:</span>
                <span class="font-bold text-gray-900">#{{ $order->id }}</span>
            </div>
            <div class="flex justify-between items-center border-b pb-2">
                <span class="text-gray-500 font-medium">{{ __('الاسم الكامل') }}:</span>
                <span class="font-bold text-gray-900">{{ $order->name }}</span>
            </div>
            <div class="flex justify-between items-center border-b pb-2">
                <span class="text-gray-500 font-medium">{{ __('رقم الهاتف') }}:</span>
                <span class="font-bold text-gray-900">{{ $order->phone }}</span>
            </div>
            <div class="flex justify-between items-center border-b pb-2">
                <span class="text-gray-500 font-medium">{{ __('المدينة والعنوان') }}:</span>
                <span class="font-semibold text-gray-800">{{ $order->city }} - {{ $order->address }}</span>
            </div>
            <div class="flex justify-between items-center pt-1 text-base">
                <span class="text-gray-900 font-bold">{{ __('المجموع الكلي') }}:</span>
                <span class="font-black text-indigo-600 text-lg">{{ number_format($order->total, 2) }} {{ __('د.ت') }}</span>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="space-y-3">
            @php
                $waNumber = \App\Models\Setting::getWhatsAppNumber();
                $waMsg = urlencode(app()->getLocale() === 'fr' 
                    ? "Bonjour, j'ai passé la commande #{$order->id} d'un montant de {$order->total} DT au nom de {$order->name}." 
                    : "مرحباً، قمت بتأكيد الطلب رقم #{$order->id} بمبلغ {$order->total} د.ت باسم {$order->name}.");
            @endphp
            <a href="https://wa.me/{{ $waNumber }}?text={{ $waMsg }}" target="_blank" class="w-full bg-[#25D366] hover:bg-[#20ba59] text-white font-bold py-3 px-6 rounded-xl text-sm flex items-center justify-center gap-2 shadow-sm transition">
                <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>
                {{ __('متابعة الطلب عبر واتساب') }}
            </a>

            <a href="{{ route('orders.invoice', $order) }}" target="_blank" class="w-full bg-white hover:bg-gray-100 text-gray-800 font-bold py-3 px-6 rounded-xl text-sm border border-gray-300 flex items-center justify-center gap-2 shadow-sm transition">
                <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                {{ __('طباعة الفاتورة') }}
            </a>

            <a href="{{ url('/') }}" class="inline-block text-sm font-semibold text-indigo-600 hover:text-indigo-800 pt-2">
                &larr; {{ __('العودة للمتجر') }}
            </a>
        </div>
    </div>
</body>
</html>
