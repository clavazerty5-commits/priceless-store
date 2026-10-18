<?php
namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\Session;
use App\Models\Product;
use App\Models\Setting;

class Navbar extends Component
{
    public int $cartCount = 0;
    public string $search = '';
    public string $phone = '+216 23 456 789';
    public string $email = 'info@priceless.com';
    public string $facebookUrl = 'https://www.facebook.com/profile.php?id=61570029157830';

    public function mount() {
        $cart = Session::get('cart', []);
        $this->cartCount = array_sum(array_column($cart, 'quantity'));

        $this->phone = Setting::where('key', 'phone')->value('value') ?? '+216 23 456 789';
        $this->email = Setting::where('key', 'email')->value('value') ?? 'info@priceless.com';
        $this->facebookUrl = Setting::where('key', 'facebook_url')->value('value') ?: 'https://www.facebook.com/profile.php?id=61570029157830';
    }

    #[On('cart-updated')]
    public function updateCartCount() {
        $cart = Session::get('cart', []);
        $this->cartCount = array_sum(array_column($cart, 'quantity'));
    }

    public function clearSearch() {
        $this->search = '';
    }

    public function render()
    {
        $searchResults = [];
        if (strlen(trim($this->search)) >= 2) {
            $searchResults = Product::where('name', 'like', '%' . trim($this->search) . '%')
                ->take(6)
                ->get();
        }

        return view('livewire.navbar', [
            'searchResults' => $searchResults,
        ]);
    }
}
