<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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