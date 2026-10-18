<?php
namespace App\Livewire;

use Livewire\Component;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Setting;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;

class Checkout extends Component
{
    public $name;
    public $phone;
    public $address;
    public $city;
    
    public $cart;
    public $subtotal = 0;
    public $delivery_fee = 0;
    public $total = 0;

    protected $rules = [
        'name' => 'required|min:3',
        'phone' => 'required|min:8',
        'address' => 'required',
        'city' => 'required',
    ];

    public function mount() {
        $this->cart = Session::get('cart', []);
        if (empty($this->cart)) {
            return redirect()->to('/cart');
        }

        if (Auth::check()) {
            $this->name = Auth::user()->name;
        }

        $this->delivery_fee = Setting::getDeliveryFee();
        
        foreach ($this->cart as $item) {
            $this->subtotal += $item['price'] * $item['quantity'];
        }
        $this->total = $this->subtotal + $this->delivery_fee;
    }

    public function placeOrder() {
        $this->validate();

        $order = Order::create([
            'user_id' => Auth::id(),
            'total' => $this->total,
            'delivery_fee' => $this->delivery_fee,
            'status' => 'pending',
            'name' => $this->name,
            'phone' => $this->phone,
            'address' => $this->address,
            'city' => $this->city,
        ]);

        foreach ($this->cart as $productId => $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $productId,
                'quantity' => $item['quantity'],
                'price' => $item['price'],
            ]);
        }

        Session::forget('cart');
        $this->dispatch('cart-updated');
        
        $admins = \App\Models\User::where('email', 'admin@admin.com')->get();
        \Filament\Notifications\Notification::make()
            ->title('طلب جديد!')
            ->body('تم استلام طلب جديد من ' . $this->name)
            ->success()
            ->sendToDatabase($admins);

        session()->flash('success', 'تم استلام طلبك بنجاح! سنتواصل معك قريباً لتأكيد التوصيل.');
        return redirect()->to('/');
    }

    public function render()
    {
        return view('livewire.checkout');
    }
}
