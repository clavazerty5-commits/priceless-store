<div class="grid grid-cols-1 lg:grid-cols-3 gap-8 pb-12">
    <!-- Form -->
    <div class="lg:col-span-2">
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow p-6 md:p-8 border border-gray-100 dark:border-gray-700">
            <h2 class="text-xl font-bold mb-6 text-gray-800 dark:text-white flex items-center gap-2">
                <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                {{ __('معلومات العميل') }}
            </h2>
            
            <form wire:submit.prevent="placeOrder">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1">{{ __('الاسم الكامل') }} *</label>
                        <input type="text" wire:model="name" required class="w-full px-4 py-2.5 border rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white text-sm">
                        @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1">{{ __('رقم الهاتف') }} *</label>
                        <input type="tel" wire:model="phone" required placeholder="مثال: 23 456 789" class="w-full px-4 py-2.5 border rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white text-sm">
                        @error('phone') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="mb-4">
                    <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1">{{ __('المدينة') }} *</label>
                    <input type="text" wire:model="city" required placeholder="تونس، سوسة، صفاقس..." class="w-full px-4 py-2.5 border rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white text-sm">
                    @error('city') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <div class="mb-6">
                    <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1">{{ __('العنوان بالتفصيل') }} *</label>
                    <textarea wire:model="address" rows="3" required placeholder="الشارع، رقم العمارة / المنزل، تفاصيل إضافية لتسهيل التوصيل..." class="w-full px-4 py-2.5 border rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white text-sm"></textarea>
                    @error('address') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <!-- Payment Method Badge -->
                <div class="bg-amber-50 dark:bg-gray-700/50 border border-amber-200 dark:border-gray-600 rounded-xl p-4 mb-6 flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-amber-500 text-white flex items-center justify-center font-bold text-lg flex-shrink-0">
                        💵
                    </div>
                    <div>
                        <h4 class="text-xs font-bold text-gray-900 dark:text-white">{{ __('الدفع عند الاستلام') }}</h4>
                        <p class="text-[11px] text-gray-600 dark:text-gray-400">{{ __('ادفع نقداً عند استلام ومعاينة الطلب في منزلك بكل أمان.') }}</p>
                    </div>
                </div>

                <button type="submit" class="w-full bg-indigo-600 text-white font-bold py-3.5 px-4 rounded-xl hover:bg-indigo-700 transition active:scale-95 flex justify-center items-center gap-2 shadow-lg shadow-indigo-500/30">
                    <span wire:loading.remove wire:target="placeOrder">{{ __('تأكيد الطلب') }} &larr;</span>
                    <span wire:loading wire:target="placeOrder">{{ __('جاري المعالجة...') }}</span>
                </button>
            </form>
        </div>
    </div>

    <!-- Summary -->
    <div class="lg:col-span-1">
        <div class="bg-gray-50 dark:bg-gray-800 rounded-2xl shadow p-6 border border-gray-200 dark:border-gray-700 sticky top-4">
            <h3 class="text-base font-bold mb-4 text-gray-800 dark:text-white">{{ __('تفاصيل الطلب') }}</h3>
            
            <div class="space-y-3 mb-4 max-h-60 overflow-y-auto divide-y dark:divide-gray-700">
                @foreach($cart as $item)
                    <div class="flex justify-between items-center pt-2 text-xs">
                        <div class="flex items-center gap-2">
                            <span class="font-bold text-gray-500">{{ $item['quantity'] }}x</span>
                            <span class="text-gray-800 dark:text-gray-200 font-semibold truncate max-w-[140px]">{{ $item['name'] }}</span>
                        </div>
                        <span class="text-gray-900 dark:text-white font-bold">{{ number_format($item['price'] * $item['quantity'], 2) }} {{ __('د.ت') }}</span>
                    </div>
                @endforeach
            </div>

            <div class="border-t dark:border-gray-700 pt-4 space-y-2 mb-4 text-xs">
                <div class="flex justify-between">
                    <span class="text-gray-600 dark:text-gray-300">{{ __('المجموع الفرعي') }}</span>
                    <span class="font-bold text-gray-900 dark:text-white">{{ number_format($subtotal, 2) }} {{ __('د.ت') }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600 dark:text-gray-300">{{ __('سعر التوصيل') }}</span>
                    <span class="font-bold text-gray-900 dark:text-white">{{ number_format($delivery_fee, 2) }} {{ __('د.ت') }}</span>
                </div>
            </div>

            <div class="border-t dark:border-gray-700 pt-4 flex justify-between items-center">
                <span class="text-base font-bold text-gray-800 dark:text-white">{{ __('المجموع الكلي') }}</span>
                <span class="text-xl font-black text-indigo-600 dark:text-indigo-400">{{ number_format($total, 2) }} {{ __('د.ت') }}</span>
            </div>
        </div>
    </div>
</div>
