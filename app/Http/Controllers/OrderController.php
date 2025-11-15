<?php
    namespace App\Http\Controllers;

    use App\Models\Order;
    use App\Models\Product;
    use App\Models\Testimonial;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Storage;
    use Illuminate\Support\Facades\Http;
    use Illuminate\Support\Facades\Log;
    

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

        public function showUploadForm(Order $order)
        {
            // Keamanan
            if ($order->user_id !== auth()->id() || $order->status !== 'pending') {
                abort(403);
            }
            return view('user.orders.upload', compact('order'));
        }

        /**
         * Menyimpan file bukti bayar.
         */
        public function storeUpload(Request $request, Order $order)
        {
            // Keamanan
            if ($order->user_id !== auth()->id() || $order->status !== 'pending') {
                abort(403);
            }

            $request->validate([
                'payment_proof' => 'required|image|mimes:jpeg,png,jpg|max:2048', // Maks 2MB
            ]);

            // Hapus bukti lama jika ada (untuk jaga-jaga)
            if ($order->payment_proof) {
                Storage::disk('public')->delete($order->payment_proof);
            }

            // Simpan file baru
            $path = $request->file('payment_proof')->store('proofs', 'public');

            // Update order
            $order->update([
                'payment_proof' => $path,
                'status' => 'waiting_confirmation', // Status baru
            ]);

            // ===================================
        //    LOGIKA NOTIFIKASI TELEGRAM (PINDAH KE SINI)
        // ===================================
        try {
            $token = env('TELEGRAM_BOT_TOKEN');
            $chatId = env('TELEGRAM_CHAT_ID');
            $user = $order->user;
            $adminLink = route('admin.orders.show', $order->id);

            // Tentukan teks pengiriman
            $jenisPengiriman = $order->shipping_method === 'pickup'
                ? "Ambil di Gudang (GRATIS)"
                : "Diantar ke Alamat";

            // Format ongkir
            $ongkirText = $order->shipping_method === 'pickup'
                ? 0
                : $order->shipping_cost;

            // Format angka
            $format = fn($n) => "Rp " . number_format($n, 0, ',', '.');

            // Hitung subtotal (total tanpa ongkir)
            $subtotal = $order->total_amount - $ongkirText;

            // ======= PESAN =======
            $message  = "🔔 *Notifikasi Pesanan Baru* 🔔\n\n";
            $message .= "Ada pesanan baru masuk!\n\n";

            $message .= "*Invoice:* {$order->invoice_number}\n";
            $message .= "*Nama:* {$user->name}\n";
            $message .= "*No. HP:* {$user->phone_number}\n";
            $message .= "*Alamat:* {$user->address}\n\n";

            // ======= DETAIL HARGA =======
            $message .= "*🧾 Rincian Harga*\n";
            $message .= "Subtotal: " . $format($subtotal) . "\n";
            $message .= "Ongkos Kirim: " . $format($ongkirText) . "\n";
            $message .= "----------------------------------------\n";
            $message .= "*Total Akhir: " . $format($order->total_amount) . "*\n\n";

            // ======= INFO PENGIRIMAN =======
            $message .= "*Metode Pengiriman:* {$jenisPengiriman}\n";

            $message .= "----------------------------------------\n";
            $message .= "Klik link berikut untuk mengecek detail pesanan:\n";
            $message .= $adminLink . "\n\n";
            $message .= "Website: https://bgdhydrofarm.com";

            // Kirim request ke API Telegram
            Http::post("https://api.telegram.org/bot{$token}/sendMessage", [
                'chat_id' => $chatId,
                'text' => $message,
                'parse_mode' => 'Markdown',
            ]);

        } catch (\Exception $e) {
            Log::error("Gagal mengirim Telegram: " . $e->getMessage());
        }

        // ===================================

            return redirect()->route('orders.index')->with('success', 'Bukti pembayaran berhasil di-upload dan sedang menunggu konfirmasi admin.');
        }
    }
    
