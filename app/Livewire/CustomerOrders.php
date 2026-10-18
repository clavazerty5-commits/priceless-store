<?php
namespace App\Livewire;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class CustomerOrders extends Component
{
    public function render()
    {
        $orders = Auth::user()->orders()->with('items.product')->latest()->get();
        return view('livewire.customer-orders', compact('orders'));
    }
}
