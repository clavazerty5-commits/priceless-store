<div class="max-w-5xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
    <h2 class="text-3xl font-extrabold text-gray-900 dark:text-white mb-8">{{ __('طلباتي') }}</h2>

    @if($orders->count() > 0)
        <div class="bg-white dark:bg-gray-800 shadow overflow-hidden rounded-lg border dark:border-gray-700">
            <ul class="divide-y divide-gray-200 dark:divide-gray-700">
                @foreach($orders as $order)
                    <li class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <div>
                                <span class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('رقم الطلب') }} #{{ $order->id }}</span>
                                <span class="ml-4 rtl:ml-0 rtl:mr-4 text-sm text-gray-500 dark:text-gray-400">{{ __('التاريخ') }}: {{ $order->created_at->format('Y-m-d') }}</span>
                            </div>
                            <div>
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    @if($order->status == 'pending') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                                    @elseif($order->status == 'processing') bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200
                                    @elseif($order->status == 'shipped') bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200
                                    @elseif($order->status == 'delivered') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                    @else bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200
                                    @endif
                                ">
                                    {{ __($order->status == 'pending' ? 'قيد الانتظار' : ($order->status == 'processing' ? 'جاري التجهيز' : ($order->status == 'shipped' ? 'في الطريق إليك' : ($order->status == 'delivered' ? 'تم التوصيل' : 'ملغي')))) }}
                                </span>
                            </div>
                        </div>
                        
                        <div class="mt-4 border-t dark:border-gray-700 pt-4">
                            <h4 class="text-sm font-medium text-gray-900 dark:text-white mb-2">{{ __('المنتجات') }}:</h4>
                            <ul class="space-y-2">
                                @foreach($order->items as $item)
                                    <li class="flex justify-between text-sm text-gray-600 dark:text-gray-300">
                                        <span>{{ $item->product->name ?? 'منتج محذوف' }} (x{{ $item->quantity }})</span>
                                        <span>{{ $item->price * $item->quantity }} د.ت</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>

                        <div class="mt-4 flex justify-between items-center border-t dark:border-gray-700 pt-4">
                            <span class="text-sm text-gray-500 dark:text-gray-400">{{ __('رسوم التوصيل') }}: {{ $order->delivery_fee }} د.ت</span>
                            <span class="text-lg font-bold text-gray-900 dark:text-white">{{ __('الإجمالي') }}: {{ $order->total }} د.ت</span>
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>
    @else
        <div class="text-center py-12 bg-white dark:bg-gray-800 rounded-lg shadow border dark:border-gray-700">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">لا توجد طلبات سابقة</h3>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">لم تقم بإجراء أي طلبات حتى الآن.</p>
            <div class="mt-6">
                <a href="{{ url('/') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    {{ __('الرئيسية') }}
                </a>
            </div>
        </div>
    @endif
</div>
