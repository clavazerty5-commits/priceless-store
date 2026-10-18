<div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden border dark:border-gray-700">
    @if(count($cart) > 0)
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-6 py-3 text-right rtl:text-right ltr:text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">{{ __('المنتج') }}</th>
                        <th class="px-6 py-3 text-right rtl:text-right ltr:text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">{{ __('السعر') }}</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">{{ __('الكمية') }}</th>
                        <th class="px-6 py-3 text-left rtl:text-left ltr:text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">{{ __('المجموع') }}</th>
                        <th class="px-6 py-3"></th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach($cart as $id => $item)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-4">
                                    <img src="{{ $item['image'] }}" alt="{{ $item['name'] }}" class="w-12 h-12 rounded object-cover">
                                    <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $item['name'] }}</div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                {{ $item['price'] }} د.ت
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <button wire:click="decreaseQuantity({{ $id }})" class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-white font-bold p-1 bg-gray-100 dark:bg-gray-700 rounded w-8 h-8 flex items-center justify-center">-</button>
                                    <span class="text-gray-900 dark:text-white font-semibold w-8 text-center">{{ $item['quantity'] }}</span>
                                    <button wire:click="increaseQuantity({{ $id }})" class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-white font-bold p-1 bg-gray-100 dark:bg-gray-700 rounded w-8 h-8 flex items-center justify-center">+</button>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-left rtl:text-left ltr:text-right text-sm font-semibold text-gray-900 dark:text-white">
                                {{ $item['price'] * $item['quantity'] }} د.ت
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-left text-sm font-medium">
                                <button wire:click="removeFromCart({{ $id }})" class="text-red-500 hover:text-red-700 transition">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="p-6 bg-gray-50 dark:bg-gray-700 border-t border-gray-200 dark:border-gray-600 flex flex-col sm:flex-row justify-between items-center gap-4">
            <div class="text-xl font-bold text-gray-900 dark:text-white">
                {{ __('المجموع الفرعي') }}: <span class="text-indigo-600 dark:text-indigo-400">{{ $total }} د.ت</span>
            </div>
            <a href="{{ route('checkout') }}" class="w-full sm:w-auto bg-indigo-600 text-white px-6 py-3 rounded-md font-semibold hover:bg-indigo-700 transition text-center shadow">
                {{ __('إتمام الطلب') }}
            </a>
        </div>
    @else
        <div class="p-10 text-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
            </svg>
            <p class="text-gray-500 dark:text-gray-400 text-lg">{{ __('سلتك فارغة حالياً.') }}</p>
            <a href="{{ url('/') }}" class="mt-4 inline-block text-indigo-600 dark:text-indigo-400 font-semibold hover:underline">{{ __('الرئيسية') }}</a>
        </div>
    @endif
</div>
