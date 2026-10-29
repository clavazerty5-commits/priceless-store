<?php
namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Session;

class Cart extends Component
{
    public $cart = [];
    public $total = 0;

    public function mount()
    {
        $this->cart = Session::get('cart', []);
        $this->calculateTotal();
    }

    public function calculateTotal()
    {
        $this->total = 0;
        foreach ($this->cart as $item) {
            $this->total += ($item['price'] ?? 0) * ($item['quantity'] ?? 1);
        }
    }

    public function increaseQuantity($productId)
    {
        if (isset($this->cart[$productId])) {
            $this->cart[$productId]['quantity']++;
            Session::put('cart', $this->cart);
            $this->calculateTotal();
            $this->dispatch('cart-updated');
        }
    }

    public function decreaseQuantity($productId)
    {
        if (isset($this->cart[$productId])) {
            if ($this->cart[$productId]['quantity'] > 1) {
                $this->cart[$productId]['quantity']--;
            } else {
                unset($this->cart[$productId]);
            }
            Session::put('cart', $this->cart);
            $this->calculateTotal();
            $this->dispatch('cart-updated');
        }
    }

    public function removeFromCart($productId)
    {
        if (isset($this->cart[$productId])) {
            unset($this->cart[$productId]);
            Session::put('cart', $this->cart);
            $this->calculateTotal();
            $this->dispatch('cart-updated');
        }
    }

    public function removeItem($productId)
    {
        $this->removeFromCart($productId);
    }

    public function render()
    {
        return view('livewire.cart');
    }
}
