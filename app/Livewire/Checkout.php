<?php
namespace App\Livewire;

use Livewire\Component;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class Checkout extends Component
{
    public $name = '';
    public $phone = '';
    public $address = '';
    public $city = '';
    
    public $cart = [];
    public $subtotal = 0;
    public $delivery_fee = 0;
    public $total = 0;

    protected $rules = [
        'name' => 'required|string|min:3|max:100',
        'phone' => 'required|string|min:8|max:20',
        'address' => 'required|string|min:5|max:255',
        'city' => 'required|string|min:2|max:100',
    ];

    protected function messages()
    {
        $isFr = app()->getLocale() === 'fr';

        return [
            'name.required' => $isFr ? 'Veuillez saisir votre nom complet.' : 'يرجى إدخال الاسم الكامل.',
            'name.min' => $isFr ? 'Le nom doit comporter au moins 3 caractères.' : 'يجب أن يكون الاسم 3 أحرف على الأقل.',
            'phone.required' => $isFr ? 'Veuillez saisir votre numéro de téléphone.' : 'يرجى إدخال رقم الهاتف.',
            'phone.min' => $isFr ? 'Le numéro de téléphone doit comporter au moins 8 chiffres.' : 'رقم الهاتف يجب أن يتكون من 8 أرقام على الأقل.',
            'address.required' => $isFr ? 'Veuillez renseigner votre adresse de livraison.' : 'يرجى إدخال العنوان بالتفصيل لتسهيل التوصيل.',
            'address.min' => $isFr ? 'Veuillez préciser une adresse complète.' : 'يرجى كتابة عنوان مفصل وواضح.',
            'city.required' => $isFr ? 'Veuillez indiquer votre ville / gouvernorat.' : 'يرجى إدخال المدينة أو المحافظة.',
        ];
    }

    public function mount()
    {
        $this->cart = Session::get('cart', []);
        if (empty($this->cart)) {
            return redirect()->to('/cart');
        }

        if (Auth::check()) {
            $user = Auth::user();
            $this->name = $user->name ?? '';
            $this->phone = $user->phone ?? '';
            $this->address = $user->address ?? '';
            $this->city = $user->city ?? '';
        }

        $this->calculateTotals();
    }

    public function calculateTotals()
    {
        $this->subtotal = 0;
        foreach ($this->cart as $item) {
            $this->subtotal += ($item['price'] ?? 0) * ($item['quantity'] ?? 1);
        }

        $standardFee = Setting::getDeliveryFee();
        $freeThreshold = Setting::getFreeDeliveryThreshold();

        if ($freeThreshold > 0 && $this->subtotal >= $freeThreshold) {
            $this->delivery_fee = 0;
        } else {
            $this->delivery_fee = $standardFee;
        }

        $this->total = $this->subtotal + $this->delivery_fee;
    }

    public function placeOrder()
    {
        $this->validate();

        $this->cart = Session::get('cart', []);
        if (empty($this->cart)) {
            $this->addError('cart', app()->getLocale() === 'fr' ? 'Votre panier est vide.' : 'سلة المشتريات فارغة.');
            return;
        }

        // Filter and verify existing products
        $productIds = array_keys($this->cart);
        $existingProducts = Product::whereIn('id', $productIds)->get()->keyBy('id');

        if ($existingProducts->isEmpty()) {
            Session::forget('cart');
            $this->addError('cart', app()->getLocale() === 'fr' ? 'Les produits du panier ne sont plus disponibles.' : 'المنتجات الموجودة في السلة لم تعد متوفرة.');
            return redirect()->to('/cart');
        }

        // Recalculate accurately
        $this->calculateTotals();

        try {
            $order = DB::transaction(function () use ($existingProducts) {
                $order = Order::create([
                    'user_id' => Auth::id(), // null for guests, ID for logged-in
                    'total' => $this->total,
                    'delivery_fee' => $this->delivery_fee,
                    'status' => 'pending',
                    'name' => trim($this->name),
                    'phone' => trim($this->phone),
                    'address' => trim($this->address),
                    'city' => trim($this->city),
                ]);

                foreach ($this->cart as $productId => $item) {
                    if (isset($existingProducts[$productId])) {
                        $prod = $existingProducts[$productId];
                        OrderItem::create([
                            'order_id' => $order->id,
                            'product_id' => $prod->id,
                            'quantity' => max(1, (int) ($item['quantity'] ?? 1)),
                            'price' => (float) ($item['price'] ?? $prod->price),
                        ]);
                    }
                }

                return $order;
            });
        } catch (\Throwable $e) {
            Log::error('Order creation failed: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            $this->addError('order_error', app()->getLocale() === 'fr' 
                ? 'Une erreur est survenue lors de l\'enregistrement de votre commande. Veuillez réessayer.' 
                : 'حدث خطأ أثناء حفظ طلبك، يرجى المحاولة مرة أخرى.');
            return;
        }

        // Safely clear cart
        Session::forget('cart');
        $this->dispatch('cart-updated');

        // Send notification to admin safely (never crash order completion)
        try {
            $admins = User::where('is_admin', true)->get();
            if ($admins->isEmpty()) {
                $admins = User::where('email', 'admin@admin.com')->get();
            }

            if ($admins->isNotEmpty()) {
                \Filament\Notifications\Notification::make()
                    ->title(app()->getLocale() === 'fr' ? 'Nouvelle commande #' . $order->id : 'طلب جديد #' . $order->id)
                    ->body(app()->getLocale() === 'fr' 
                        ? 'Commande de ' . $this->name . ' d\'un montant de ' . number_format($this->total, 2) . ' DT'
                        : 'قام ' . $this->name . ' بطلب جديد بمبلغ ' . number_format($this->total, 2) . ' د.ت')
                    ->success()
                    ->sendToDatabase($admins);
            }
        } catch (\Throwable $e) {
            Log::warning('Filament notification failed (non-blocking): ' . $e->getMessage());
        }

        return redirect()->route('order.success', $order);
    }

    public function render()
    {
        return view('livewire.checkout');
    }
}
