<div class="grid grid-cols-1 lg:grid-cols-3 gap-8 pb-12">
    <!-- Form -->
    <div class="lg:col-span-2">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 border dark:border-gray-700">
            <h2 class="text-xl font-bold mb-6 text-gray-800 dark:text-white">{{ __('بيانات التوصيل') }}</h2>
            
            <form wire:submit.prevent="placeOrder">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('الاسم الكامل') }}</label>
                        <input type="text" wire:model="name" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('رقم الهاتف') }}</label>
                        <input type="text" wire:model="phone" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        @error('phone') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('المدينة') }}</label>
                    <input type="text" wire:model="city" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    @error('city') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('العنوان التفصيلي') }}</label>
                    <textarea wire:model="address" rows="3" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"></textarea>
                    @error('address') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <button type="submit" class="w-full bg-indigo-600 text-white font-bold py-3 px-4 rounded-lg hover:bg-indigo-700 transition active:scale-95 flex justify-center items-center gap-2">
                    <span wire:loading.remove wire:target="placeOrder">{{ __('تأكيد الطلب') }}</span>
                    <span wire:loading wire:target="placeOrder">{{ __('جاري المعالجة...') }}</span>
                </button>
            </form>
        </div>
    </div>

    <!-- Summary -->
    <div class="lg:col-span-1">
        <div class="bg-gray-50 dark:bg-gray-800 rounded-lg shadow p-6 border dark:border-gray-700 sticky top-4">
            <h3 class="text-lg font-bold mb-4 text-gray-800 dark:text-white">{{ __('ملخص الطلب') }}</h3>
            
            <div class="space-y-4 mb-4">
                @foreach($cart as $item)
                    <div class="flex justify-between">
                        <div class="flex gap-2">
                            <span class="text-gray-500">{{ $item['quantity'] }}x</span>
                            <span class="text-gray-800 dark:text-gray-200 truncate max-w-[150px]">{{ $item['name'] }}</span>
                        </div>
                        <span class="text-gray-900 dark:text-white font-medium">{{ $item['price'] * $item['quantity'] }} د.ت</span>
                    </div>
                @endforeach
            </div>

            <div class="border-t dark:border-gray-700 pt-4 space-y-2 mb-4">
                <div class="flex justify-between text-sm">
                    <span class="text-gray-600 dark:text-gray-300">{{ __('المجموع الفرعي') }}</span>
                    <span class="font-medium text-gray-900 dark:text-white">{{ $subtotal }} د.ت</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-600 dark:text-gray-300">{{ __('رسوم التوصيل') }}</span>
                    <span class="font-medium text-gray-900 dark:text-white">{{ $delivery_fee }} د.ت</span>
                </div>
            </div>

            <div class="border-t dark:border-gray-700 pt-4 flex justify-between items-center">
                <span class="text-lg font-bold text-gray-800 dark:text-white">{{ __('الإجمالي') }}</span>
                <span class="text-xl font-bold text-indigo-600 dark:text-indigo-400">{{ $total }} د.ت</span>
            </div>
        </div>
    </div>
</div>
