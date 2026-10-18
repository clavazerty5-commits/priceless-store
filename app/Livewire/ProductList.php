<?php
namespace App\Livewire;

use Livewire\Component;
use App\Models\Product;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ProductList extends Component
{
    public $products;

    public function mount() {
        $this->products = Product::with('category')->latest()->get();
    }

    public function addToCart($productId) {
        $product = Product::find($productId);
        if (!$product) return;

        $cart = Session::get('cart', []);
        
        $imageUrl = $product->image;
        if ($imageUrl && !Str::startsWith($imageUrl, ['http://', 'https://'])) {
            $imageUrl = Storage::url($imageUrl);
        }
        
        if (isset($cart[$productId])) {
            $cart[$productId]['quantity']++;
        } else {
            $cart[$productId] = [
                'name' => $product->name,
                'price' => $product->price,
                'quantity' => 1,
                'image' => $imageUrl
            ];
        }

        Session::put('cart', $cart);
        $this->dispatch('cart-updated');
        $this->dispatch('product-added-to-cart', name: $product->name);
    }

    public function render()
    {
        return view('livewire.product-list');
    }
}
