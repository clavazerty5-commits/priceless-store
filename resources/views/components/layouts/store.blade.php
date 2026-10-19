<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'PRICELESS - متجر إلكتروني متكامل' }}</title>

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
        $pixelId = \App\Models\Setting::get('facebook_pixel_id');
        $facebookUrl = \App\Models\Setting::get('facebook_url') ?: 'https://www.facebook.com/profile.php?id=61570029157830';
        $waNumber = \App\Models\Setting::getWhatsAppNumber();
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
         class="fixed top-5 left-1/2 -translate-x-1/2 z-50 bg-gray-900 text-white px-5 py-3.5 rounded-2xl shadow-2xl flex items-center gap-3 border border-gray-700 max-w-md w-full mx-4">
        <div class="w-8 h-8 rounded-full bg-green-500 flex items-center justify-center text-white flex-shrink-0">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
        </div>
        <div class="flex-1 min-w-0">
            <h4 class="text-xs font-bold text-gray-200">{{ __('تمت إضافة المنتج إلى السلة بنجاح! 🛍️') }}</h4>
            <p class="text-xs font-semibold text-white truncate" x-text="toast.productName"></p>
        </div>
        <a href="{{ route('cart') }}" class="text-xs bg-indigo-600 hover:bg-indigo-700 text-white font-bold px-3 py-1.5 rounded-xl transition flex-shrink-0">
            {{ __('معاينة السلة') }}
        </a>
    </div>

    <!-- Navigation / Header Component -->
    <livewire:navbar />

    <!-- Main Content Slot -->
    <main class="min-h-screen">
        {{ $slot }}
    </main>

    <!-- Floating WhatsApp Button -->
    @php
        $waDefaultMsg = urlencode(app()->getLocale() === 'fr' 
            ? "Bonjour, j'aimerais avoir plus de renseignements sur vos produits." 
            : "السلام عليكم، أريد الاستفسار عن منتجاتكم.");
    @endphp
    <a href="https://wa.me/{{ $waNumber }}?text={{ $waDefaultMsg }}" 
       target="_blank" 
       rel="noopener noreferrer" 
       class="fixed bottom-6 right-6 rtl:right-auto rtl:left-6 z-40 bg-[#25D366] hover:bg-[#20ba59] text-white p-3.5 rounded-full shadow-2xl hover:scale-110 transition-transform duration-300 flex items-center gap-2 group"
       title="{{ __('تواصل معنا عبر واتساب') }}">
        <svg class="w-7 h-7 fill-current" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>
        <span class="hidden md:inline font-bold text-xs pr-1 rtl:pr-0 rtl:pl-1">{{ __('اطلب عبر واتساب') }}</span>
        <!-- Pulse ring -->
        <span class="absolute -top-1 -right-1 rtl:-right-auto rtl:-left-1 flex h-3 w-3">
            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
            <span class="relative inline-flex rounded-full h-3 w-3 bg-green-500"></span>
        </span>
    </a>

    <!-- Footer -->
    <footer class="bg-gray-900 text-gray-300 mt-20 border-t border-gray-800">
        <!-- Features Bar -->
        <div class="border-b border-gray-800 py-10 bg-gray-950">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-8 text-center">
                    <div class="flex flex-col items-center">
                        <div class="w-12 h-12 bg-indigo-50 dark:bg-gray-800 rounded-full flex items-center justify-center mb-2">
                            <svg class="w-6 h-6 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                        </div>
                        <h4 class="font-bold mb-1 text-white">{{ __('توصيل سريع') }}</h4>
                        <p class="text-xs text-gray-400">{{ __('توصيل لكافة مدن تونس') }}</p>
                    </div>
                    <div class="flex flex-col items-center">
                        <div class="w-12 h-12 bg-indigo-50 dark:bg-gray-800 rounded-full flex items-center justify-center mb-2">
                            <svg class="w-6 h-6 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                        </div>
                        <h4 class="font-bold mb-1 text-white">{{ __('دعم فني 24/7') }}</h4>
                        <p class="text-xs text-gray-400">{{ __('فريقنا جاهز لمساعدتك دائماً') }}</p>
                    </div>
                    <div class="flex flex-col items-center">
                        <div class="w-12 h-12 bg-indigo-50 dark:bg-gray-800 rounded-full flex items-center justify-center mb-2">
                            <svg class="w-6 h-6 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <h4 class="font-bold mb-1 text-white">{{ __('ضمان الجودة والاسترجاع') }}</h4>
                        <p class="text-xs text-gray-400">{{ __('استرجاع سهل خلال 14 يوماً') }}</p>
                    </div>
                    <div class="flex flex-col items-center">
                        <div class="w-12 h-12 bg-indigo-50 dark:bg-gray-800 rounded-full flex items-center justify-center mb-2">
                            <svg class="w-6 h-6 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                        </div>
                        <h4 class="font-bold mb-1 text-white">{{ __('الدفع عند الاستلام') }}</h4>
                        <p class="text-xs text-gray-400">{{ __('ادفع بأمان عند استلام طلبك') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer Links -->
        <div id="about-us" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <!-- Contact Info -->
                <div class="space-y-4">
                    <div class="flex items-center gap-2">
                        <img src="{{ asset('images/logo.jpg') }}" alt="PRICELESS" class="h-10 w-auto rounded">
                        <span class="text-2xl font-bold text-white tracking-wide">PRICELESS</span>
                    </div>
                    <p class="text-xs text-gray-400 leading-relaxed">
                        {{ __('متجر إلكتروني يقدم أفضل المنتجات بجودة عالية وأسعار منافسة مع ضمان الرضا 100%.') }}
                    </p>
                    <ul class="space-y-2 text-xs mt-4 text-gray-300">
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg> 
                            {{ \App\Models\Setting::get('phone', '+216 23 456 789') }}
                        </li>
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg> 
                            {{ \App\Models\Setting::get('email', 'info@priceless.com') }}
                        </li>
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg> 
                            {{ \App\Models\Setting::get('address', 'تونس العاصمة، تونس') }}
                        </li>
                    </ul>
                    <div class="pt-2">
                        <a href="{{ $facebookUrl }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-2 bg-[#1877F2] hover:bg-[#166fe5] text-white text-xs font-bold px-3.5 py-2 rounded-xl transition shadow">
                            <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                            <span>{{ __('صفحتنا على فايسبوك') }}</span>
                        </a>
                    </div>
                </div>

                <!-- Quick Links -->
                <div>
                    <h3 class="text-white font-bold mb-4 uppercase text-sm tracking-wider">{{ __('روابط سريعة') }}</h3>
                    <ul class="space-y-2 text-xs">
                        <li><a href="{{ url('/') }}" class="hover:text-white transition flex items-center gap-1.5"><svg class="w-3 h-3 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg> {{ __('الرئيسية') }}</a></li>
                        <li><a href="{{ url('/') }}#products-section" class="hover:text-white transition flex items-center gap-1.5"><svg class="w-3 h-3 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg> {{ __('جميع المنتجات') }}</a></li>
                        <li><a href="{{ route('dashboard') }}" class="hover:text-white transition flex items-center gap-1.5"><svg class="w-3 h-3 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg> {{ __('تتبع طلبك') }}</a></li>
                    </ul>
                </div>

                <!-- Customer Account -->
                <div>
                    <h3 class="text-white font-bold mb-4 uppercase text-sm tracking-wider">{{ __('حسابي') }}</h3>
                    <ul class="space-y-2 text-xs">
                        <li><a href="{{ route('dashboard') }}" class="hover:text-white transition flex items-center gap-1.5"><svg class="w-3 h-3 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg> {{ __('طلباتي السابقة') }}</a></li>
                        <li><a href="{{ route('cart') }}" class="hover:text-white transition flex items-center gap-1.5"><svg class="w-3 h-3 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg> {{ __('سلة المشتريات') }}</a></li>
                        <li><a href="{{ route('login') }}" class="hover:text-white transition flex items-center gap-1.5"><svg class="w-3 h-3 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg> {{ __('تسجيل الدخول') }}</a></li>
                        <li><a href="{{ route('register') }}" class="hover:text-white transition flex items-center gap-1.5"><svg class="w-3 h-3 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg> {{ __('إنشاء حساب') }}</a></li>
                    </ul>
                </div>

                <!-- Newsletter -->
                <div>
                    <h3 class="text-white font-bold mb-4 uppercase text-sm tracking-wider">{{ __('النشرة البريدية') }}</h3>
                    <p class="text-xs text-gray-400 mb-4">{{ __('اشترك لتصلك أحدث العروض والكوبونات الحصرية.') }}</p>
                    <form onsubmit="event.preventDefault(); alert('Merci pour votre inscription ! / شكراً لاشتراكك!');" class="space-y-2">
                        <input type="email" required placeholder="{{ __('بريدك الإلكتروني...') }}" class="w-full px-3 py-2 text-xs bg-gray-800 border border-gray-700 rounded-lg text-white focus:outline-none focus:border-indigo-500">
                        <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 rounded-lg text-xs transition">
                            {{ __('اشتراك') }}
                        </button>
                    </form>
                </div>
            </div>

            <!-- Bottom Copyright -->
            <div class="border-t border-gray-800 mt-12 pt-6 flex flex-col md:flex-row justify-between items-center text-xs text-gray-500 gap-4">
                <p>&copy; {{ date('Y') }} PRICELESS. {{ __('جميع الحقوق محفوظة.') }}</p>
                <div class="flex items-center gap-4">
                    <span>💵 {{ __('الدفع عند الاستلام') }}</span>
                    <span>•</span>
                    <span>🇹🇳 {{ __('توصيل لكافة مدن تونس') }}</span>
                </div>
            </div>
        </div>
    </footer>

    @livewireScripts
</body>
</html>
