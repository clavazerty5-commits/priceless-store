<div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4">
    @foreach($products as $product)
        <div class="bg-white dark:bg-gray-800 rounded shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden flex flex-col group hover:shadow-md transition-shadow">
            <!-- Image -->
            <div class="relative pt-[100%] overflow-hidden bg-gray-50">
                <img src="{{ Str::startsWith($product->image, ['http://', 'https://']) ? $product->image : Storage::url($product->image) }}" alt="{{ $product->name }}" class="absolute inset-0 w-full h-full object-contain p-4 group-hover:scale-105 transition-transform duration-300">
                
                @if($product->old_price)
                    <div class="absolute top-2 right-2 rtl:right-auto rtl:left-2 bg-red-500 text-white text-[10px] font-bold px-2 py-1 rounded-sm uppercase">
                        {{ __('تخفيض') }}
                    </div>
                @endif
            </div>

            <!-- Details -->
            <div class="p-4 flex-grow flex flex-col items-center text-center">
                <!-- Category -->
                <a href="#" class="text-[11px] text-gray-500 dark:text-gray-400 hover:text-indigo-600 uppercase tracking-wide mb-1">{{ $product->category->name ?? __('إلكترونيات') }}</a>
                
                <!-- Rating -->
                <div class="flex items-center gap-0.5 text-yellow-400 mb-2">
                    @for($i=0; $i<5; $i++)
                        <svg class="w-3 h-3 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                    @endfor
                    <span class="text-[10px] text-gray-400 ml-1 rtl:mr-1">(0 Review)</span>
                </div>

                <!-- Product Name -->
                <h3 class="text-sm font-semibold text-gray-800 dark:text-white mb-2 line-clamp-2 hover:text-indigo-600 transition-colors">
                    <a href="#">{{ $product->name }}</a>
                </h3>

                <div class="mt-auto"></div>

                <!-- Price -->
                <div class="flex items-center justify-center gap-2 mb-4">
                    <span class="text-gray-900 dark:text-white font-bold text-sm">{{ $product->price }} د.ت</span>
                    @if($product->old_price)
                        <span class="text-red-400 line-through text-xs">{{ $product->old_price }} د.ت</span>
                    @endif
                </div>
            </div>

            <!-- Add to Cart Button -->
            <button wire:click="addToCart({{ $product->id }})" class="w-full bg-[#0e8ce4] text-white text-xs font-bold py-3 uppercase tracking-wider hover:bg-[#0c7ac7] transition-colors flex items-center justify-center">
                <span wire:loading.remove wire:target="addToCart({{ $product->id }})">ADD TO CART</span>
                <span wire:loading wire:target="addToCart({{ $product->id }})">...</span>
            </button>
        </div>
    @endforeach
</div>
