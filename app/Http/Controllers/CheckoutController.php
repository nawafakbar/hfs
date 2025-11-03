<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use Cart;

class CheckoutController extends Controller
{
    /**
     * Helper untuk mendapatkan instance keranjang milik user yang login
     */
    private function getCart()
    {
        $userId = auth()->id();
        return Cart::session($userId);
    }

    public function index()
    {
        if ($this->getCart()->isEmpty()) { // <-- Gunakan helper
            return redirect()->route('cart.index')->withErrors('Keranjang Anda kosong!');
        }
        return view('user.checkout');
    }

    public function process(Request $request)
    {
        if ($this->getCart()->isEmpty()) { // <-- Gunakan helper
            return redirect()->route('cart.index')->withErrors('Keranjang Anda kosong.');
        }

        $user = auth()->user();
        
        $order = Order::create([
            'user_id' => $user->id,
            'invoice_number' => 'INV-' . time() . '-' . $user->id,
            'total_amount' => $this->getCart()->getTotal(), // <-- Gunakan helper
            'shipping_address' => $user->address,
            'status' => 'pending',
        ]);

        foreach ($this->getCart()->getContent() as $item) { // <-- Gunakan helper
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item->id,
                'quantity' => $item->quantity,
                'price' => $item->price,
            ]);
        }

        $this->getCart()->clear(); // <-- Gunakan helper

        return redirect()->route('checkout.payment', $order->invoice_number);
    }

    // METHOD BARU: Untuk menangani halaman pembayaran
    public function payment(Order $order)
    {
        // Konfigurasi Midtrans
        \Midtrans\Config::$serverKey    = config('midtrans.server_key');
        \Midtrans\Config::$isProduction = config('midtrans.is_production');
        \Midtrans\Config::$isSanitized  = true;
        \Midtrans\Config::$is3ds        = true;

        // Siapkan parameter untuk Midtrans
        $params = [
            'transaction_details' => [ 'order_id' => $order->invoice_number, 'gross_amount' => $order->total_amount ],
            'customer_details' => [ 'first_name' => $order->user->name, 'email' => $order->user->email, 'phone' => $order->user->phone_number ],
        ];

        // Dapatkan Snap Token
        $snapToken = \Midtrans\Snap::getSnapToken($params);

        // Tampilkan view pembayaran dengan snapToken
        return view('user.payment', compact('snapToken', 'order'));
    }

    public function success(Request $request)
    {
        // Ambil invoice number dari URL
        $invoice_number = $request->query('invoice');
        
        // Cari order berdasarkan invoice number. Jika tidak ada, redirect ke home.
        $order = Order::where('invoice_number', $invoice_number)
                      ->where('user_id', auth()->id())
                      ->first();
        
        if (!$order) {
            return redirect()->route('home');
        }

        // Kirim data order ke view
        return view('user.checkout_success', compact('order'));
    }
}