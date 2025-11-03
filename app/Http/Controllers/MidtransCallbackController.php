<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Product; // <-- 1. TAMBAHKAN IMPORT MODEL PRODUCT
use Illuminate\Support\Facades\DB; // <-- 2. TAMBAHKAN IMPORT DB
use Midtrans\Config;
use Midtrans\Notification;

class MidtransCallbackController extends Controller
{
    public function handle(Request $request)
    {
        // Set konfigurasi Midtrans
        Config::$isProduction = config('midtrans.is_production');
        Config::$serverKey = config('midtrans.server_key');

        try {
            // Buat instance notifikasi Midtrans
            $notification = new Notification();
        } catch (\Exception $e) {
            return response()->json(['error' => 'Invalid notification'], 400);
        }

        // Ambil order_id dan status transaksi
        $order_id = $notification->order_id;
        $status = $notification->transaction_status;
        $fraud = $notification->fraud_status;

        // Cari order di database berdasarkan invoice_number (yaitu order_id dari midtrans)
        // Kita juga langsung ambil relasi 'orderItems'
        $order = Order::with('orderItems')->where('invoice_number', $order_id)->first();

        if (!$order) {
            return response()->json(['error' => 'Order not found'], 404);
        }

        //logika pengurangan stok

        // 1. Handle jika transaksi LUNAS (settlement/capture)
        if (($status == 'capture' || $status == 'settlement') && $fraud == 'accept') {
            
            // 2. Pastikan kita hanya proses jika statusnya masih 'pending'
            if ($order->status == 'pending') {
                
                // 3. Gunakan DB Transaction untuk keamanan data
                DB::transaction(function () use ($order) {
                    
                    // 4. Update status order menjadi 'paid'
                    $order->update(['status' => 'paid']);

                    // 5. Loop semua item di order tersebut dan kurangi stok
                    foreach ($order->orderItems as $item) {
                        
                        // Temukan produk yang bersangkutan
                        $product = Product::find($item->product_id);
                        
                        if ($product) {
                            // Kurangi stoknya
                            $newStock = $product->stock - $item->quantity;
                            $product->stock = $newStock >= 0 ? $newStock : 0; // Pastikan stok tidak minus
                            $product->save();
                        }
                    }
                });
            }
        }
        
        // Handle status lain (misal: kadaluarsa atau dibatalkan)
        else if (in_array($status, ['expire', 'cancel', 'deny']) && $order->status == 'pending') {
             $order->update(['status' => 'cancelled']);
        }

        return response()->json(['message' => 'Notification handled.']);
    }
}