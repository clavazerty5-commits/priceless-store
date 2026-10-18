<x-layouts.store title="{{ __('الرئيسية - متجر PRICELESS') }}">

    @php
        $banners = \App\Models\Banner::where('is_active', true)->orderBy('order', 'asc')->get();
        $categories = \App\Models\Category::all();
    @endphp

    <!-- Dynamic Hero Carousel Area -->
    <div class="relative bg-gradient-to-r from-indigo-50 via-purple-50 to-pink-50 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900 overflow-hidden"
         x-data="{ 
             activeSlide: 0, 
             totalSlides: {{ $banners->count() > 0 ? $banners->count() : 1 }},
             next() { this.activeSlide = (this.activeSlide + 1) % this.totalSlides; },
             prev() { this.activeSlide = (this.activeSlide - 1 + this.totalSlides) % this.totalSlides; }
         }"
         x-init="setInterval(() => next(), 6000)">

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 md:py-20 relative">
            @if($banners->count() > 0)
                @foreach($banners as $index => $banner)
                    <div x-show="activeSlide === {{ $index }}" 
                         x-transition:enter="transition ease-out duration-500"
                         x-transition:enter-start="opacity-0 translate-x-8"
                         x-transition:enter-end="opacity-100 translate-x-0"
                         x-transition:leave="transition ease-in duration-300"
                         x-transition:leave-start="opacity-100 translate-x-0"
                         x-transition:leave-end="opacity-0 -translate-x-8"
                         class="flex flex-col md:flex-row items-center justify-between gap-8"
                         @if($index !== 0) style="display: none;" @endif>
                        
                        <div class="md:w-1/2 text-center rtl:md:text-right ltr:md:text-left z-10">
                            @if($banner->badge)
                                <span class="inline-block py-1.5 px-4 rounded-full bg-indigo-100 text-indigo-700 dark:bg-indigo-900/50 dark:text-indigo-300 text-xs font-bold uppercase tracking-wider mb-4 shadow-sm">
                                    {{ $banner->badge }}
                                </span>
                            @endif
                            <h2 class="text-4xl md:text-6xl font-black text-gray-900 dark:text-white mb-4 tracking-tight leading-tight">
                                {{ $banner->title }}
                            </h2>
                            <p class="text-base md:text-lg text-gray-600 dark:text-gray-300 mb-8 max-w-lg leading-relaxed">
                                {{ $banner->subtitle ?? __('استمتع بأحدث التخفيضات الحصرية والتوصيل السريع لجميع المنتجات.') }}
                            </p>
                            <a href="{{ $banner->link ?: '#products-section' }}" class="inline-flex items-center gap-2 bg-[#0e8ce4] text-white px-8 py-3.5 rounded-full font-bold uppercase tracking-wider hover:bg-blue-600 transition shadow-lg shadow-blue-500/30 transform hover:-translate-y-0.5">
                                <span>{{ __('تسوق الآن') }}</span>
                                <svg class="w-4 h-4 rtl:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                            </a>
                        </div>

                        <div class="md:w-1/2 flex justify-center relative">
                            @if($banner->image)
                                <img src="{{ Storage::url($banner->image) }}" alt="{{ $banner->title }}" class="max-h-80 md:max-h-96 object-contain rounded-2xl shadow-xl">
                            @else
                                <div class="w-72 h-72 md:w-80 md:h-80 bg-gradient-to-tr from-indigo-300 to-purple-300 dark:from-indigo-800 dark:to-purple-900 rounded-3xl shadow-2xl flex items-center justify-center p-8 transform rotate-3 hover:rotate-0 transition duration-500">
                                    <svg class="w-36 h-36 text-white opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            @else
                <!-- Fallback Banner -->
                <div class="flex flex-col md:flex-row items-center justify-between gap-8">
                    <div class="md:w-1/2 text-center rtl:md:text-right ltr:md:text-left z-10">
                        <span class="inline-block py-1 px-3 rounded-full bg-indigo-100 text-indigo-600 text-xs font-bold uppercase tracking-wider mb-4">{{ __('عروض حصرية') }}</span>
                        <h2 class="text-4xl md:text-6xl font-black text-gray-900 dark:text-white mb-4">SUPER SALE</h2>
                        <p class="text-lg text-gray-600 dark:text-gray-300 mb-8">{{ __('خصومات حصرية تصل إلى 50% على أفضل المنتجات.') }}</p>
                        <a href="#products-section" class="inline-block bg-[#0e8ce4] text-white px-8 py-3 rounded-full font-bold uppercase tracking-wider hover:bg-blue-600 transition shadow-lg">{{ __('تسوق الآن') }}</a>
                    </div>
                    <div class="md:w-1/2 flex justify-center">
                        <div class="w-72 h-72 bg-gradient-to-tr from-indigo-200 to-pink-200 rounded-2xl shadow-xl flex items-center justify-center">
                            <svg class="w-28 h-28 text-indigo-500 opacity-60" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Carousel Controls -->
            @if($banners->count() > 1)
                <div class="flex items-center justify-center gap-2 mt-8">
                    @foreach($banners as $index => $banner)
                        <button @click="activeSlide = {{ $index }}" 
                                :class="activeSlide === {{ $index }} ? 'w-8 bg-indigo-600' : 'w-2.5 bg-gray-300 dark:bg-gray-600'" 
                                class="h-2.5 rounded-full transition-all duration-300"></button>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    <!-- Top Categories Section -->
    @if($categories->count() > 0)
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-12">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-xl md:text-2xl font-bold text-gray-900 dark:text-white">{{ __('أقسام المتجر المميزة') }}</h3>
                    <p class="text-xs text-gray-500 mt-0.5">{{ __('اختر من بين تشكيلاتنا المتنوعة') }}</p>
                </div>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4">
                @foreach($categories as $category)
                    <a href="#products-section" class="bg-white dark:bg-gray-800 rounded-2xl p-4 text-center border border-gray-100 dark:border-gray-700 shadow-sm hover:shadow-md hover:border-indigo-500 transition group flex flex-col items-center">
                        <div class="w-16 h-16 rounded-full bg-indigo-50 dark:bg-gray-700 flex items-center justify-center mb-3 group-hover:scale-110 transition duration-300 overflow-hidden">
                            @if($category->image)
                                <img src="{{ Storage::url($category->image) }}" alt="{{ $category->name }}" class="w-full h-full object-cover">
                            @else
                                <svg class="w-8 h-8 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
                            @endif
                        </div>
                        <h4 class="text-xs font-bold text-gray-800 dark:text-gray-200 group-hover:text-indigo-600 transition">{{ $category->name }}</h4>
                        <span class="text-[10px] text-gray-400 mt-1">{{ $category->products()->count() }} {{ __('منتج') }}</span>
                    </a>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Flash Sale Banner with Live Realtime Countdown Timer -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-12"
         x-data="{ 
             hours: 18, 
             minutes: 42, 
             seconds: 55,
             init() {
                 setInterval(() => {
                     if (this.seconds > 0) {
                         this.seconds--;
                     } else {
                         this.seconds = 59;
                         if (this.minutes > 0) {
                             this.minutes--;
                         } else {
                             this.minutes = 59;
                             if (this.hours > 0) this.hours--;
                         }
                     }
                 }, 1000);
             }
         }">
        <div class="bg-gradient-to-r from-purple-700 via-indigo-700 to-blue-700 rounded-3xl p-6 md:p-8 flex flex-col md:flex-row items-center justify-between shadow-xl gap-6">
            <div class="text-center md:text-right rtl:md:text-right ltr:md:text-left">
                <span class="inline-block bg-white/20 text-white text-[11px] font-bold px-3 py-1 rounded-full uppercase tracking-wider mb-2">{{ __('⚡ تخفيضات البرق') }}</span>
                <h3 class="text-2xl md:text-3xl font-extrabold text-white tracking-tight">{{ __('عرض خاص ومحدود جداً!') }}</h3>
                <p class="text-xs md:text-sm text-indigo-100 mt-1">{{ __('استفد من الأسعار التفضيلية قبل انتهاء الوقت المتبقي.') }}</p>
            </div>

            <!-- Countdown Timer Blocks -->
            <div class="flex items-center gap-3">
                <div class="bg-white/10 backdrop-blur-md border border-white/20 rounded-2xl px-4 py-3 text-center min-w-[70px]">
                    <span class="block text-2xl font-black text-white" x-text="String(hours).padStart(2, '0')">18</span>
                    <span class="text-[10px] text-indigo-200 uppercase font-semibold">{{ __('ساعة') }}</span>
                </div>
                <span class="text-white font-bold text-xl">:</span>
                <div class="bg-white/10 backdrop-blur-md border border-white/20 rounded-2xl px-4 py-3 text-center min-w-[70px]">
                    <span class="block text-2xl font-black text-white" x-text="String(minutes).padStart(2, '0')">42</span>
                    <span class="text-[10px] text-indigo-200 uppercase font-semibold">{{ __('دقيقة') }}</span>
                </div>
                <span class="text-white font-bold text-xl">:</span>
                <div class="bg-white/10 backdrop-blur-md border border-white/20 rounded-2xl px-4 py-3 text-center min-w-[70px]">
                    <span class="block text-2xl font-black text-yellow-300" x-text="String(seconds).padStart(2, '0')">55</span>
                    <span class="text-[10px] text-indigo-200 uppercase font-semibold">{{ __('ثانية') }}</span>
                </div>
            </div>

            <a href="#products-section" class="bg-white text-indigo-700 hover:bg-yellow-400 hover:text-gray-900 px-7 py-3 rounded-full font-extrabold text-xs uppercase tracking-wider transition shadow-lg flex-shrink-0">
                {{ __('شاهد العروض') }} &larr;
            </a>
        </div>
    </div>

    <!-- Products Section -->
    <div id="products-section" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-14">
        <div class="flex flex-col md:flex-row justify-between items-center mb-8 border-b dark:border-gray-700 pb-4">
            <div>
                <h2 class="text-2xl md:text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight">{{ __('أفضل المنتجات المختارة') }}</h2>
                <p class="text-xs text-gray-500 mt-1">{{ __('تسوق أفضل العروض والمنتجات بأفضل الأسعار التنافسية') }}</p>
            </div>
        </div>

        <!-- Render Products List Component -->
        <livewire:product-list />
    </div>

</x-layouts.store>
