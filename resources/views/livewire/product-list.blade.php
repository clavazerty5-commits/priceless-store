<div x-data="{ 
    quickViewModal: false, 
    selectedProduct: null,
    openQuickView(prod) {
        this.selectedProduct = prod;
        this.quickViewModal = true;
    }
}">
    @php
        $waNumber = \App\Models\Setting::getWhatsAppNumber();
        $isFr = app()->getLocale() === 'fr';
    @endphp

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-5">
        @foreach($products as $product)
            @php
                $imgSrc = Str::startsWith($product->image, ['http://', 'https://']) ? $product->image : Storage::url($product->image);
                $prodJson = htmlspecialchars(json_encode([
                    'id' => $product->id,
                    'name' => $product->name,
                    'price' => $product->price,
                    'old_price' => $product->old_price,
                    'description' => $product->description,
                    'category' => $product->category->name ?? '',
                    'stock' => $product->stock,
                    'image' => $imgSrc,
                ]), ENT_QUOTES, 'UTF-8');

                $waMsg = urlencode($isFr 
                    ? "Bonjour, je souhaite commander le produit : {$product->name} (Prix: {$product->price} DT)." 
                    : "السلام عليكم، أود طلب المنتج: {$product->name} بسعر {$product->price} د.ت.");
            @endphp

            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden flex flex-col group hover:shadow-xl transition-all duration-300">
                <!-- Image Container with Badges & Quick View -->
                <div class="relative pt-[100%] overflow-hidden bg-gray-50 dark:bg-gray-700 cursor-pointer" @click='openQuickView({!! $prodJson !!})'>
                    <img src="{{ $imgSrc }}" alt="{{ $product->name }}" class="absolute inset-0 w-full h-full object-contain p-4 group-hover:scale-105 transition-transform duration-300">
                    
                    @if($product->old_price && $product->old_price > $product->price)
                        @php
                            $discountPct = round((($product->old_price - $product->price) / $product->old_price) * 100);
                        @endphp
                        <div class="absolute top-3 right-3 rtl:right-auto rtl:left-3 bg-red-500 text-white text-[11px] font-black px-2.5 py-1 rounded-full shadow uppercase">
                            -{{ $discountPct }}%
                        </div>
                    @endif

                    <!-- Quick View Overlay Button -->
                    <div class="absolute inset-0 bg-black/20 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                        <span class="bg-white text-gray-900 text-xs font-bold py-2 px-4 rounded-full shadow-lg transform translate-y-2 group-hover:translate-y-0 transition duration-200 flex items-center gap-1.5">
                            <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                            {{ __('نظرة سريعة') }}
                        </span>
                    </div>
                </div>

                <!-- Product Details -->
                <div class="p-4 flex-grow flex flex-col items-center text-center">
                    <!-- Category -->
                    <span class="text-[11px] text-gray-500 dark:text-gray-400 font-semibold uppercase tracking-wider mb-1">
                        {{ $product->category->name ?? __('إلكترونيات') }}
                    </span>
                    
                    <!-- Rating -->
                    <div class="flex items-center gap-1 text-amber-400 mb-2">
                        @for($i=0; $i<5; $i++)
                            <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                        @endfor
                        <span class="text-[11px] text-gray-500 font-bold ml-1 rtl:mr-1">5.0</span>
                    </div>

                    <!-- Product Name -->
                    <h3 class="text-sm font-bold text-gray-900 dark:text-white mb-2 line-clamp-2 hover:text-indigo-600 transition cursor-pointer" @click='openQuickView({!! $prodJson !!})'>
                        {{ $product->name }}
                    </h3>

                    <div class="mt-auto"></div>

                    <!-- Price -->
                    <div class="flex items-center justify-center gap-2 mb-3">
                        <span class="text-gray-900 dark:text-white font-black text-base">{{ number_format($product->price, 2) }} {{ __('د.ت') }}</span>
                        @if($product->old_price)
                            <span class="text-red-400 line-through text-xs font-semibold">{{ number_format($product->old_price, 2) }} {{ __('د.ت') }}</span>
                        @endif
                    </div>
                </div>

                <!-- Action Buttons: Add to Cart & WhatsApp Order -->
                <div class="p-3 pt-0 flex flex-col gap-2">
                    <button wire:click="addToCart({{ $product->id }})" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold py-2.5 px-3 rounded-xl transition flex items-center justify-center gap-1.5 shadow-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        <span wire:loading.remove wire:target="addToCart({{ $product->id }})">{{ __('أضف إلى السلة') }}</span>
                        <span wire:loading wire:target="addToCart({{ $product->id }})">...</span>
                    </button>

                    <a href="https://wa.me/{{ $waNumber }}?text={{ $waMsg }}" target="_blank" rel="noopener noreferrer" class="w-full bg-[#25D366] hover:bg-[#20ba59] text-white text-xs font-bold py-2 px-3 rounded-xl transition flex items-center justify-center gap-1.5 shadow-sm">
                        <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>
                        <span>{{ __('اطلب عبر واتساب') }}</span>
                    </a>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Quick View Modal -->
    <div x-show="quickViewModal" 
         style="display: none;"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 overflow-y-auto bg-black/60 backdrop-blur-sm flex items-center justify-center p-4">
        
        <div class="bg-white dark:bg-gray-800 rounded-3xl max-w-2xl w-full overflow-hidden shadow-2xl relative border dark:border-gray-700" 
             @click.away="quickViewModal = false">
            
            <!-- Close Button -->
            <button @click="quickViewModal = false" class="absolute top-4 right-4 rtl:right-auto rtl:left-4 z-10 w-9 h-9 rounded-full bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200 flex items-center justify-center hover:bg-gray-200 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>

            <template x-if="selectedProduct">
                <div class="grid grid-cols-1 md:grid-cols-2">
                    <!-- Product Image -->
                    <div class="bg-gray-50 dark:bg-gray-900 p-8 flex items-center justify-center">
                        <img :src="selectedProduct.image" :alt="selectedProduct.name" class="max-h-72 w-auto object-contain">
                    </div>

                    <!-- Product Info -->
                    <div class="p-6 md:p-8 flex flex-col justify-between text-start">
                        <div>
                            <span class="text-xs font-bold text-indigo-600 uppercase tracking-wider block mb-1" x-text="selectedProduct.category"></span>
                            <h2 class="text-xl font-black text-gray-900 dark:text-white mb-2" x-text="selectedProduct.name"></h2>
                            
                            <div class="flex items-center gap-2 mb-4">
                                <span class="text-2xl font-black text-gray-900 dark:text-white" x-text="Number(selectedProduct.price).toFixed(2) + ' {{ __('د.ت') }}'"></span>
                                <template x-if="selectedProduct.old_price">
                                    <span class="text-sm text-red-400 line-through font-semibold" x-text="Number(selectedProduct.old_price).toFixed(2) + ' {{ __('د.ت') }}'"></span>
                                </template>
                            </div>

                            <p class="text-xs text-gray-600 dark:text-gray-300 leading-relaxed mb-6" x-text="selectedProduct.description"></p>
                        </div>

                        <!-- Modal Actions -->
                        <div class="space-y-3">
                            <button @click="$wire.addToCart(selectedProduct.id); quickViewModal = false" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-4 rounded-xl text-xs uppercase tracking-wider flex items-center justify-center gap-2 shadow-sm transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                <span>{{ __('أضف إلى السلة') }}</span>
                            </button>

                            <a :href="'https://wa.me/{{ $waNumber }}?text=' + encodeURIComponent('{{ $isFr ? "Bonjour, je souhaite commander : " : "السلام عليكم، أود طلب المنتج: " }}' + selectedProduct.name + ' ({{ __('السعر') }}: ' + selectedProduct.price + ' {{ __('د.ت') }})')" 
                               target="_blank" 
                               rel="noopener noreferrer" 
                               class="w-full bg-[#25D366] hover:bg-[#20ba59] text-white font-bold py-3 px-4 rounded-xl text-xs flex items-center justify-center gap-2 shadow-sm transition">
                                <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>
                                <span>{{ __('اطلب عبر واتساب') }}</span>
                            </a>
                        </div>
                    </div>
                </div>
            </template>
        </div>
    </div>
</div>
