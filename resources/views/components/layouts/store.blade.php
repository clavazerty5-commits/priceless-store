<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'PRICELESS - متجرك الإلكتروني المفضل' }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=cairo:400,600,700,800&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles

    <style>
        body { font-family: 'Cairo', sans-serif; }
    </style>

    @php
        $pixelId = \App\Models\Setting::where('key', 'facebook_pixel_id')->value('value');
        $facebookUrl = \App\Models\Setting::where('key', 'facebook_url')->value('value') ?: 'https://www.facebook.com/profile.php?id=61570029157830';
    @endphp

    @if(!empty($pixelId))
    <!-- Meta Pixel Code -->
    <script>
    !function(f,b,e,v,n,t,s)
    {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
    n.callMethod.apply(n,arguments):n.queue.push(arguments)};
    if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
    n.queue=[];t=b.createElement(e);t.async=!0;
    t.src=v;s=b.getElementsByTagName(e)[0];
    s.parentNode.insertBefore(t,s)}(window, document,'script',
    'https://connect.facebook.net/en_US/fbevents.js');
    fbq('init', '{{ $pixelId }}');
    fbq('track', 'PageView');
    </script>
    <noscript><img height="1" width="1" style="display:none"
    src="https://www.facebook.com/tr?id={{ $pixelId }}&ev=PageView&noscript=1"
    /></noscript>
    <!-- End Meta Pixel Code -->
    @endif
</head>
<body class="bg-gray-50 text-gray-900 antialiased dark:bg-gray-900 dark:text-gray-100 selection:bg-indigo-500 selection:text-white" 
      x-data="{ 
          darkMode: false,
          toast: { show: false, message: '', productName: '' },
          showToast(name) {
              this.toast.productName = name;
              this.toast.show = true;
              setTimeout(() => { this.toast.show = false; }, 4000);
          }
      }" 
      x-init="
          window.addEventListener('product-added-to-cart', event => {
              showToast(event.detail.name);
              if (typeof fbq === 'function') {
                  fbq('track', 'AddToCart', { content_name: event.detail.name });
              }
          });
      "
      :class="{ 'dark': darkMode }">

    <!-- Toast Notification (Floating) -->
    <div x-show="toast.show" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 transform -translate-y-4 scale-95"
         x-transition:enter-end="opacity-100 transform translate-y-0 scale-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 transform translate-y-0 scale-100"
         x-transition:leave-end="opacity-0 transform -translate-y-4 scale-95"
         style="display: none;"
         class="fixed top-6 left-1/2 transform -translate-x-1/2 z-[100] max-w-md w-full px-4">
        <div class="bg-white dark:bg-gray-800 border-2 border-emerald-500 shadow-2xl rounded-2xl p-4 flex items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-emerald-100 text-emerald-600 rounded-full flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                </div>
                <div>
                    <h4 class="text-sm font-bold text-gray-900 dark:text-white">{{ __('تمت الإضافة إلى السلة بنجاح! 🛍️') }}</h4>
                    <p class="text-xs text-gray-500 dark:text-gray-400 line-clamp-1" x-text="toast.productName"></p>
                </div>
            </div>
            <a href="{{ route('cart') }}" class="text-xs font-bold bg-emerald-600 hover:bg-emerald-700 text-white px-3.5 py-2 rounded-xl transition flex-shrink-0">
                {{ __('عرض السلة') }}
            </a>
        </div>
    </div>

    <div class="min-h-screen flex flex-col">
        <!-- Header / Navbar -->
        <livewire:navbar />

        <!-- Main Content -->
        <main class="flex-grow">
            {{ $slot }}
        </main>

        <!-- Footer -->
        <footer class="mt-16 bg-[#0B253A] text-gray-300">
            <!-- Features Row -->
            <div class="bg-white dark:bg-gray-800 py-8 border-b dark:border-gray-700 shadow-sm">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-8 text-center text-gray-700 dark:text-gray-300">
                        <div class="flex flex-col items-center">
                            <div class="w-12 h-12 bg-indigo-50 dark:bg-gray-700 rounded-full flex items-center justify-center mb-2">
                                <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                            </div>
                            <h4 class="font-bold mb-1">{{ __('توصيل سريع') }}</h4>
                            <p class="text-xs text-gray-500">{{ __('شحن لجميع ولايات تونس') }}</p>
                        </div>
                        <div class="flex flex-col items-center">
                            <div class="w-12 h-12 bg-indigo-50 dark:bg-gray-700 rounded-full flex items-center justify-center mb-2">
                                <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                            </div>
                            <h4 class="font-bold mb-1">{{ __('دعم فني متواصل 24/7') }}</h4>
                            <p class="text-xs text-gray-500">{{ __('فريقنا جاهز لمساعدتك دائماً') }}</p>
                        </div>
                        <div class="flex flex-col items-center">
                            <div class="w-12 h-12 bg-indigo-50 dark:bg-gray-700 rounded-full flex items-center justify-center mb-2">
                                <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                            <h4 class="font-bold mb-1">{{ __('ضمان استرجاع الأموال') }}</h4>
                            <p class="text-xs text-gray-500">{{ __('استرجاع سهل خلال 14 يوماً') }}</p>
                        </div>
                        <div class="flex flex-col items-center">
                            <div class="w-12 h-12 bg-indigo-50 dark:bg-gray-700 rounded-full flex items-center justify-center mb-2">
                                <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                            </div>
                            <h4 class="font-bold mb-1">{{ __('الدفع عند الاستلام') }}</h4>
                            <p class="text-xs text-gray-500">{{ __('ادفع بأمان عند وصول طلبك') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer Links & Newsletter -->
            <div id="about-us" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                    <!-- Contact Info -->
                    <div class="space-y-4">
                        <div class="flex items-center gap-2">
                            <img src="{{ asset('images/logo.jpg') }}" alt="PRICELESS" class="h-10 w-auto rounded">
                            <span class="text-2xl font-bold text-white tracking-wide">PRICELESS</span>
                        </div>
                        <p class="text-xs text-gray-400 leading-relaxed">
                            {{ __('متجرك الإلكتروني الرائد لتقديم أفضل المنتجات بأفضل الأسعار وبجودة مضمونة 100%.') }}
                        </p>
                        <ul class="space-y-2 text-xs mt-4 text-gray-300">
                            <li class="flex items-center gap-2"><svg class="w-4 h-4 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg> {{ \App\Models\Setting::where('key', 'phone')->value('value') ?? '+216 23 456 789' }}</li>
                            <li class="flex items-center gap-2"><svg class="w-4 h-4 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg> {{ \App\Models\Setting::where('key', 'email')->value('value') ?? 'info@priceless.com' }}</li>
                            <li class="flex items-center gap-2"><svg class="w-4 h-4 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg> {{ \App\Models\Setting::where('key', 'address')->value('value') ?? 'تونس، شارع الحبيب بورقيبة' }}</li>
                        </ul>
                        <div class="pt-2">
                            <a href="{{ $facebookUrl }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-2 bg-[#1877F2] hover:bg-[#166fe5] text-white text-xs font-bold px-3.5 py-2 rounded-xl transition shadow">
                                <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                                <span>{{ __('صفحتنا على فيسبوك') }}</span>
                            </a>
                        </div>
                    </div>

                    <!-- Quick Links -->
                    <div>
                        <h3 class="text-white font-bold mb-4 uppercase text-sm tracking-wider">{{ __('روابط سريعة') }}</h3>
                        <ul class="space-y-2 text-xs">
                            <li><a href="{{ url('/') }}" class="hover:text-white transition flex items-center gap-1.5"><svg class="w-3 h-3 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg> {{ __('الرئيسية') }}</a></li>
                            <li><a href="{{ url('/') }}#products-section" class="hover:text-white transition flex items-center gap-1.5"><svg class="w-3 h-3 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg> {{ __('جميع المنتجات') }}</a></li>
                            <li><a href="{{ route('dashboard') }}" class="hover:text-white transition flex items-center gap-1.5"><svg class="w-3 h-3 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg> {{ __('تتبع طلبي') }}</a></li>
                        </ul>
                    </div>

                    <!-- My Account Links -->
                    <div>
                        <h3 class="text-white font-bold mb-4 uppercase text-sm tracking-wider">{{ __('حسابي') }}</h3>
                        <ul class="space-y-2 text-xs">
                            <li><a href="{{ route('dashboard') }}" class="hover:text-white transition flex items-center gap-1.5"><svg class="w-3 h-3 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg> {{ __('طلباتي السابقة') }}</a></li>
                            <li><a href="{{ route('cart') }}" class="hover:text-white transition flex items-center gap-1.5"><svg class="w-3 h-3 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg> {{ __('سلة التسوق') }}</a></li>
                            @auth
                                <li><a href="{{ route('dashboard') }}" class="hover:text-white transition flex items-center gap-1.5"><svg class="w-3 h-3 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg> {{ __('لوحة التحكم') }}</a></li>
                            @else
                                <li><a href="{{ route('login') }}" class="hover:text-white transition flex items-center gap-1.5"><svg class="w-3 h-3 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg> {{ __('تسجيل الدخول') }}</a></li>
                                <li><a href="{{ route('register') }}" class="hover:text-white transition flex items-center gap-1.5"><svg class="w-3 h-3 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg> {{ __('إنشاء حساب جديد') }}</a></li>
                            @endauth
                        </ul>
                    </div>

                    <!-- Newsletter -->
                    <div>
                        <h3 class="text-white font-bold mb-4 uppercase text-sm tracking-wider">{{ __('النشرة البريدية') }}</h3>
                        <p class="text-xs mb-4 text-gray-400">{{ __('اشترك للحصول على أحدث التخفيضات وكوبونات الخصم الحصرية.') }}</p>
                        <form class="flex" onsubmit="event.preventDefault(); alert('شكراً لاشتراكك في النشرة البريدية! 🎉');">
                            <input type="email" required placeholder="{{ __('بريدك الإلكتروني...') }}" class="w-full px-3 py-2 text-xs text-gray-900 bg-white border-0 focus:ring-0 rounded-r-none rounded-l-md rtl:rounded-r-md rtl:rounded-l-none">
                            <button type="submit" class="bg-indigo-600 text-white px-4 py-2 text-xs font-bold rounded-l-none rounded-r-md rtl:rounded-l-md rtl:rounded-r-none hover:bg-indigo-700 transition">
                                {{ __('اشترك') }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            
            <!-- Copyrights -->
            <div class="bg-[#061826] py-4 text-center text-xs text-gray-400 border-t border-gray-800">
                <p>&copy; {{ date('Y') }} PRICELESS. {{ __('جميع الحقوق محفوظة.') }}</p>
            </div>
        </footer>
    </div>

    @livewireScripts
</body>
</html>
