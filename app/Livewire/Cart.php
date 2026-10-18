<?php
namespace App\Livewire;
use Livewire\Component;
use Illuminate\Support\Facades\Session;

class Cart extends Component
{
    public $cart = [];
    public $total = 0;

    public function mount() {
        $this->cart = Session::get('cart', []);
        $this->calculateTotal();
    }

    public function calculateTotal() {
        $this->total = 0;
        foreach ($this->cart as $item) {
            $this->total += $item['price'] * $item['quantity'];
        }
    }

    public function removeItem($productId) {
        unset($this->cart[$productId]);
        Session::put('cart', $this->cart);
        $this->calculateTotal();
        $this->dispatch('cart-updated');
    }

    public function render()
    {
        return view('livewire.cart');
    }
}
