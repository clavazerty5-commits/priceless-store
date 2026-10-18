<div>
    <!-- Top Bar -->
    <div class="bg-gray-100 dark:bg-gray-900 border-b dark:border-gray-800 text-xs py-2">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex justify-between items-center">
            <div class="flex items-center gap-4 text-gray-600 dark:text-gray-400">
                <span class="flex items-center gap-1">
                    <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                    {{ $email }}
                </span>
                <span class="flex items-center gap-1">
                    <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                    {{ $phone }}
                </span>
            </div>
            <div class="flex items-center gap-4">
                <!-- Theme Switcher -->
                <button @click="darkMode = !darkMode" class="text-gray-600 dark:text-gray-400 hover:text-indigo-600 transition" title="تبديل الوضع">
                    <svg x-show="!darkMode" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path></svg>
                    <svg x-show="darkMode" style="display: none;" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                </button>
                <!-- Language Switcher -->
                <div x-data="{ open: false }" class="relative">
                    <button @click="open = !open" @click.away="open = false" class="text-gray-600 dark:text-gray-400 hover:text-indigo-600 flex items-center gap-1 font-semibold">
                        {{ app()->getLocale() == 'ar' ? 'العربية' : 'Français' }}
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </button>
                    <div x-show="open" style="display: none;" class="absolute right-0 mt-2 w-24 bg-white dark:bg-gray-800 border dark:border-gray-700 shadow-lg rounded z-50">
                        <a href="{{ route('lang.switch', 'ar') }}" class="block px-4 py-2 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">العربية</a>
                        <a href="{{ route('lang.switch', 'fr') }}" class="block px-4 py-2 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">Français</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Header -->
    <div class="bg-white dark:bg-gray-900 py-4 shadow-sm relative">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row justify-between items-center gap-4">
            <!-- Logo -->
            <a href="{{ url('/') }}" class="flex items-center gap-2">
                <img src="{{ asset('images/logo.jpg') }}" alt="PRICELESS" class="h-12 w-auto rounded shadow-sm">
                <span class="text-3xl font-extrabold text-indigo-600 tracking-tight">PRICELESS</span>
            </a>

            <!-- Search Bar with Live Instant Suggestions -->
            <div class="flex-1 max-w-2xl w-full mx-4 relative" x-data="{ openResults: true }" @click.away="openResults = false">
                <div class="relative">
                    <input 
                        type="text" 
                        wire:model.live.debounce.300ms="search" 
                        @focus="openResults = true"
                        placeholder="{{ __('ابحث عن منتجك المفضل...') }}" 
                        class="w-full pl-4 pr-12 py-2.5 border-2 border-indigo-600 rounded-full focus:outline-none focus:ring-2 focus:ring-indigo-300 dark:bg-gray-800 dark:text-white dark:border-indigo-500 text-sm shadow-sm transition">
                    
                    @if(strlen($search) > 0)
                        <button wire:click="clearSearch" class="absolute left-12 rtl:right-auto rtl:left-12 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    @endif

                    <button class="absolute left-0 top-0 h-full px-5 bg-indigo-600 text-white rounded-l-full hover:bg-indigo-700 rtl:right-0 rtl:left-auto rtl:rounded-r-full rtl:rounded-l-none transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </button>
                </div>

                <!-- Instant Live Search Dropdown -->
                @if(strlen(trim($search)) >= 2)
                    <div x-show="openResults" class="absolute left-0 right-0 mt-2 bg-white dark:bg-gray-800 border dark:border-gray-700 rounded-xl shadow-2xl z-50 overflow-hidden divide-y dark:divide-gray-700">
                        @forelse($searchResults as $item)
                            <div class="p-3 hover:bg-indigo-50 dark:hover:bg-gray-700 transition flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="w-12 h-12 bg-gray-100 rounded-lg overflow-hidden flex-shrink-0 flex items-center justify-center">
                                        <img src="{{ Str::startsWith($item->image, ['http://', 'https://']) ? $item->image : Storage::url($item->image) }}" alt="{{ $item->name }}" class="w-full h-full object-contain">
                                    </div>
                                    <div>
                                        <h4 class="text-sm font-bold text-gray-800 dark:text-white line-clamp-1">{{ $item->name }}</h4>
                                        <div class="flex items-center gap-2 mt-0.5">
                                            <span class="text-indigo-600 dark:text-indigo-400 font-bold text-xs">{{ $item->price }} د.ت</span>
                                            @if($item->old_price)
                                                <span class="text-red-400 line-through text-[11px]">{{ $item->old_price }} د.ت</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <button wire:click="$dispatch('add-to-cart-from-search', { id: {{ $item->id }} })" class="text-xs bg-indigo-600 text-white px-3 py-1.5 rounded-lg hover:bg-indigo-700 font-bold transition flex items-center gap-1">
                                    <span>أضف للسلة</span>
                                </button>
                            </div>
                        @empty
                            <div class="p-4 text-center text-sm text-gray-500 dark:text-gray-400">
                                {{ __('لا توجد منتجات مطابقة لـ') }} "{{ $search }}"
                            </div>
                        @endforelse
                    </div>
                @endif
            </div>

            <!-- Icons -->
            <div class="flex items-center gap-6">
                <!-- User Account -->
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" @click.away="open = false" class="flex flex-col items-center text-gray-700 dark:text-gray-300 hover:text-indigo-600 transition">
                        <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        <span class="text-xs font-semibold">{{ Auth::check() ? Auth::user()->name : __('حسابي') }}</span>
                    </button>
                    <div x-show="open" style="display: none;" class="absolute left-1/2 transform -translate-x-1/2 mt-2 w-48 bg-white dark:bg-gray-800 border dark:border-gray-700 shadow-lg rounded-md py-2 z-50">
                        @auth
                            @if(Auth::user()->email === 'admin@admin.com')
                                <a href="{{ url('/admin') }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-indigo-50 dark:hover:bg-gray-700 font-bold text-indigo-600">{{ __('لوحة الإدارة') }}</a>
                            @else
                                <a href="{{ route('dashboard') }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-indigo-50 dark:hover:bg-gray-700">{{ __('لوحة التحكم') }}</a>
                                <a href="{{ route('dashboard') }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-indigo-50 dark:hover:bg-gray-700">{{ __('طلباتي') }}</a>
                            @endif
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full text-right px-4 py-2 text-sm text-red-600 hover:bg-red-50 dark:hover:bg-gray-700">{{ __('تسجيل الخروج') }}</button>
                            </form>
                        @else
                            <a href="{{ route('login') }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-indigo-50 dark:hover:bg-gray-700">{{ __('تسجيل الدخول') }}</a>
                            <a href="{{ route('register') }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-indigo-50 dark:hover:bg-gray-700">{{ __('حساب جديد') }}</a>
                        @endauth
                    </div>
                </div>

                <!-- Wishlist Icon -->
                <a href="#" class="flex flex-col items-center relative text-gray-700 dark:text-gray-300 hover:text-indigo-600 transition">
                    <svg class="w-6 h-6 mb-1 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                    <span class="absolute top-0 right-0 -mr-2 -mt-1 bg-indigo-600 text-white text-[10px] w-4 h-4 rounded-full flex justify-center items-center font-bold">0</span>
                    <span class="text-xs font-semibold">{{ __('المفضلة') }}</span>
                </a>

                <!-- Cart Icon -->
                <a href="{{ route('cart') }}" class="flex flex-col items-center relative text-gray-700 dark:text-gray-300 hover:text-indigo-600 transition">
                    <svg class="w-6 h-6 mb-1 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    <span class="absolute top-0 right-0 -mr-2 -mt-1 bg-red-500 text-white text-[10px] w-4 h-4 rounded-full flex justify-center items-center font-bold animate-pulse">{{ $cartCount }}</span>
                    <span class="text-xs font-semibold">{{ __('عربة التسوق') }}</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Navigation Menu -->
    <div class="bg-indigo-600 dark:bg-indigo-800 shadow-md">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <ul class="flex items-center gap-8 text-white font-medium text-sm py-3 overflow-x-auto whitespace-nowrap">
                <li><a href="{{ url('/') }}" class="hover:text-indigo-200 flex items-center gap-1 font-bold">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>    
                    {{ __('الرئيسية') }}
                </a></li>
                <li><a href="{{ url('/') }}#products-section" class="hover:text-indigo-200">{{ __('المنتجات') }}</a></li>
                <li><a href="#about-us" class="hover:text-indigo-200">{{ __('من نحن') }}</a></li>
                <li><a href="{{ route('dashboard') }}" class="hover:text-indigo-200">{{ __('تتبع الطلب') }}</a></li>
            </ul>
        </div>
    </div>
</div>
