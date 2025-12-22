<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\Product;


class OrderController extends Controller
{
    /**
     * Menampilkan daftar semua pesanan.
     */
    public function index()
    {
        $orders = Order::with('user')->latest()->paginate(15);
        return view('admin.orders.index', compact('orders'));
    }

    /**
     * Tampilkan form tambah pesanan manual (POS).
     */
    public function create()
    {
        // Ambil produk yang stoknya ada
        $products = Product::where('stock', '>', 0)->get();
        return view('admin.orders.create', compact('products'));
    }

    /**
     * Simpan pesanan manual.
     */
    public function store(Request $request)
    {
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'products' => 'required|array',
            'products.*' => 'exists:products,id',
            'quantities' => 'required|array',
            'quantities.*' => 'integer|min:1',
        ]);

        try {
            DB::transaction(function () use ($request) {
                // 1. Hitung Total & Siapkan Data
                $totalAmount = 0;
                $orderItemsData = [];

                foreach ($request->products as $index => $productId) {
                    $product = Product::findOrFail($productId);
                    $qty = $request->quantities[$index];

                    // Cek stok lagi biar aman
                    if ($product->stock < $qty) {
                        throw new \Exception("Stok {$product->name} tidak cukup (Sisa: {$product->stock})");
                    }

                    $price = $product->discount_price ?? $product->price;
                    $subtotal = $price * $qty;
                    $totalAmount += $subtotal;

                    // Kurangi Stok Langsung
                    $product->decrement('stock', $qty);

                    // Siapkan data item
                    $orderItemsData[] = [
                        'product_id' => $product->id,
                        'quantity' => $qty,
                        'price' => $price,
                    ];
                }

                // 2. Buat Order Baru
                // Kita kosongkan user_id karena ini tamu offline (pastikan kolom user_id di DB nullable, kalau tidak, isi ID admin)
                $order = Order::create([
                    'user_id' => auth()->id(), // Atau Auth::id() jika mau dicatat atas nama admin
                    'invoice_number' => 'INV-OFFLINE-' . strtoupper(Str::random(6)) . '-' . time(),
                    'total_amount' => $totalAmount,
                    'status' => 'completed', // Langsung selesai karena bayar di tempat
                    'payment_status' => 'paid', // Opsional jika ada kolom ini
                    'shipping_address' => 'Warehouse (Pembelian Langsung)',
                    'shipping_method' => 'pickup',
                    'shipping_cost' => 0,
                    'payment_method' => 'cash', // Atau manual transfer
                    // Simpan nama pembeli di catatan atau kolom khusus jika ada. 
                    // Jika tidak ada kolom customer_name, kita bisa akali di shipping_address
                    'notes' => 'Pembeli: ' . $request->customer_name, 
                ]);

                // 3. Simpan Item Order
                foreach ($orderItemsData as $item) {
                    $order->orderItems()->create($item);
                }
            });

            return redirect()->route('admin.orders.index')->with('success', 'Pesanan manual berhasil dibuat & stok dikurangi.');

        } catch (\Exception $e) {
            return back()->with('error', 'Gagal membuat pesanan: ' . $e->getMessage());
        }
    }

    /**
     * Menampilkan detail satu pesanan.
     */
    public function show(Order $order)
    {
        // Eager load relasi orderItems dan product di dalamnya
        $order->load('orderItems.product');
        return view('admin.orders.show', compact('order'));
    }

    /**
     * Memperbarui status pesanan.
     */
    public function update(Request $request, string $id)
    {
        $order = Order::findOrFail($id);
        $oldStatus = $order->status; // Ambil status lama
        $newStatus = $request->status; // Ambil status baru dari form

        // Update status order
        $order->update([
            'status' => $newStatus,
        ]);

        // ==========================================================
        // LOGIKA PENGURANGAN STOK
        // ==========================================================
        // Cek jika status diubah menjadi 'paid' (atau 'packaging') 
        // DAN status sebelumnya BUKAN 'paid' (untuk mencegah pengurangan ganda)
        if (
            ($newStatus == 'paid' || $newStatus == 'packaging' || $newStatus == 'shipping' || $newStatus == 'completed') &&
            ($oldStatus == 'pending' || $oldStatus == 'waiting_confirmation')
        ) {

            // Tandai kapan pembayaran dikonfirmasi
            $order->update(['payment_confirmed_at' => now()]);

            // Mulai transaksi database
            DB::transaction(function () use ($order) {
                $order->load('orderItems.product'); // Load relasi

                foreach ($order->orderItems as $item) {
                    $product = $item->product;
                    if ($product) {
                        // Kurangi stok
                        $newStock = $product->stock - $item->quantity;
                        $product->stock = $newStock >= 0 ? $newStock : 0;
                        $product->save();
                    }
                }
            });
        }

        return redirect()->route('admin.orders.show', $order->id)->with('success', 'Status pesanan berhasil diperbarui.');
    }
    
    /**
     * TAMBAHKAN METHOD BARU INI UNTUK MENGHAPUS PESANAN
     */
    public function destroy(Order $order)
    {
        // Hapus semua item terkait (jika tidak ada onDelete('cascade'))
        // $order->orderItems()->delete(); // Tidak perlu jika migrasi sudah benar

        // Hapus pesanan utama
        $order->delete();

        return redirect()->route('admin.orders.index')
                         ->with('success', 'Pesanan berhasil dihapus.');
    }
    
    /**
     * Method create(), store(), edit(), destroy() bisa dikosongkan 
     * atau dihilangkan jika admin tidak akan melakukan aksi ini.
     */
}