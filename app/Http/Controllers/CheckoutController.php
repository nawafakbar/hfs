<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Order;
use App\Models\OrderItem;
use Cart;

class CheckoutController extends Controller
{
    private function getCart()
    {
        $userId = auth()->id();
        return Cart::session($userId);
    }

    public function index()
    {
        if ($this->getCart()->getTotal() <= 0) { 
            return redirect()->route('cart.index')->withErrors('Keranjang Anda kosong!');
        }

        return view('user.checkout');
    }

    public function process(Request $request)
    {
        $total = $this->getCart()->getTotal();
        if ($total <= 0) {
            return redirect()->route('cart.index')->withErrors('Keranjang Anda kosong atau totalnya nol.');
        }

        $user = auth()->user();

        $shipping_method = $request->shipping_method; // delivery | pickup

        // --- HITUNG ONGKIR ---
        if ($shipping_method === 'pickup') {
            $ongkir = 0;
            $shipping_address = "Pickup di Gudang BGD Hydrofarm";
        } else {
            $ongkir = config('ongkir.zona_padang')[$user->kecamatan] ?? 0;
            $shipping_address = $user->address;
        }

        $subtotal = $total;
        $total_amount = $subtotal + $ongkir;

        // --- SIMPAN ORDER ---
        $order = Order::create([
            'user_id' => $user->id,
            'invoice_number' => 'INV-' . time() . '-' . $user->id,
            'subtotal' => $subtotal,
            'shipping_cost' => $ongkir,
            'total_amount' => $total_amount,
            'shipping_method' => $shipping_method,
            'shipping_address' => $shipping_address,
            'status' => 'pending',
        ]);

        // --- SIMPAN ITEM ORDER ---
        foreach ($this->getCart()->getContent() as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item->id,
                'quantity' => $item->quantity,
                'price' => $item->price,
            ]);
        }

        // --- KOSONGKAN CART ---
        $this->getCart()->clear();

        return redirect()->route('checkout.payment', $order->invoice_number);
    }

    public function payment(Order $order)
    {
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        return view('user.payment', compact('order'));
    }

    public function success(Request $request)
    {
        $invoice_number = $request->query('invoice');
        $order = Order::where('invoice_number', $invoice_number)->first();
        
        if (!$order) {
            return view('user.checkout_success_simple');
        }

        $order->load('orderItems.product');
        return view('user.checkout_success', compact('order'));
    }
}
