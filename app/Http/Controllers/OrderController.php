<?php
    namespace App\Http\Controllers;

    use App\Models\Order;
    use Illuminate\Http\Request;
    use App\Models\Testimonial;

    class OrderController extends Controller
    {
        /**
         * Menampilkan daftar semua pesanan milik user yang sedang login.
         */
        public function index()
        {
            $orders = Order::where('user_id', auth()->id())
                           ->latest()
                           ->paginate(10);

            return view('user.orders.index', compact('orders'));
        }

        /**
         * Menampilkan detail satu pesanan.
         */
        public function show(Order $order)
        {
            /// Keamanan
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        $order->load('orderItems.product', 'user');

        // Ambil ID produk yang sudah diulas oleh user ini
        $reviewedProductIds = Testimonial::where('user_id', auth()->id())
                                ->pluck('product_id')
                                ->toArray();

        return view('user.orders.show', compact('order', 'reviewedProductIds'));
        }

        // METHOD BARU UNTUK CETAK INVOICE
        public function print(Order $order)
        {
            // Keamanan: Pastikan user hanya bisa print order miliknya sendiri
            if ($order->user_id !== auth()->id()) {
                abort(403);
            }

            // Load relasi yang dibutuhkan
            $order->load('orderItems.product', 'user');
            
            // Kembalikan ke view 'print' yang akan kita buat
            return view('user.orders.print', compact('order'));
        }
    }
    
