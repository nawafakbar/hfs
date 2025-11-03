<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

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
    public function update(Request $request, Order $order)
    {
        $request->validate(['status' => 'required|string']);

        $order->update(['status' => $request->status]);

        return redirect()->route('admin.orders.show', $order)
                         ->with('success', 'Status pesanan berhasil diperbarui.');
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