<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Cart; // <-- Gunakan facade Cart

class CartController extends Controller
{
    /**
     * Helper untuk mendapatkan instance keranjang milik user yang login
     */
    private function getCart()
    {
        // Dapatkan ID user yang sedang login
        $userId = auth()->id();
        
        // Buat atau ambil sesi keranjang yang terikat dengan ID user
        return Cart::session($userId);
    }

    public function index()
    {
        $cartItems = $this->getCart()->getContent();
        $subTotal = $this->getCart()->getSubTotal();
        $total = $this->getCart()->getTotal();

        // Kirim data ke view
        return view('user.cart', compact('cartItems', 'subTotal', 'total'));
    }

    public function add(Request $request)
    {
        $product = Product::findOrFail($request->product_id);

        $this->getCart()->add([
            'id' => $product->id,
            'name' => $product->name,
            'price' => $product->discount_price ?? $product->price,
            'quantity' => (int) $request->quantity, // pastikan integer
            'attributes' => [
                'image' => $product->image,
            ]
        ]);

        return redirect()->route('cart.index')->with('success', 'Produk berhasil ditambahkan ke keranjang!');
    }

    public function update(Request $request, $itemId)
    {
        $this->getCart()->update($itemId, [
            'quantity' => [
                'relative' => false,
                'value' => (int) $request->quantity // pastikan integer
            ],
        ]);

        return back()->with('success', 'Jumlah produk berhasil diperbarui.');
    }

    public function remove($itemId)
    {
        $this->getCart()->remove($itemId);
        return back()->with('success', 'Produk berhasil dihapus dari keranjang.');
    }
}
