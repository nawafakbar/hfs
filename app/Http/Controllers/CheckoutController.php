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

        // --- HITUNG ONGKIR BERDASARKAN KECAMATAN ---
        $ongkir = config('ongkir.zona_padang')[$user->kecamatan] ?? 0;

        // --- SIMPAN ORDER ---
        $order = Order::create([
            'user_id' => $user->id,
            'invoice_number' => 'INV-' . time() . '-' . $user->id,
            'total_amount' => $total + $ongkir,
            'shipping_cost' => $ongkir,
            'shipping_address' => $user->address,
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

        // --- PINDAH KE HALAMAN PEMBAYARAN ---
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
