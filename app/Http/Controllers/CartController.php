<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Cart;

class CartController extends Controller
{
    private function getCart()
    {
        $userId = auth()->id();
        return Cart::session($userId);
    }

    public function index()
    {
        $cartItems = $this->getCart()->getContent();
        $subTotal = $this->getCart()->getSubTotal();
        $total = $this->getCart()->getTotal();

        return view('user.cart', compact('cartItems', 'subTotal', 'total'));
    }

    public function add(Request $request)
    {
        $product = Product::findOrFail($request->product_id);

        if ($product->stock <= 0) {
            return back()->with('error', 'Maaf, stok produk ini sedang kosong.');
        }

        $cartItem = $this->getCart()->get($product->id);
        $currentQtyInCart = $cartItem ? $cartItem->quantity : 0;
        
        $requestedQty = (int) $request->quantity;
        $totalQty = $currentQtyInCart + $requestedQty;

        if ($totalQty > $product->stock) {
            $sisaBisaDibeli = $product->stock - $currentQtyInCart;
            if ($sisaBisaDibeli <= 0) {
                $pesan = "Stok tidak cukup. Anda sudah memiliki maks stok ($currentQtyInCart) di keranjang.";
            } else {
                $pesan = "Stok tidak cukup. Anda sudah punya $currentQtyInCart di keranjang. Sisa yang bisa diambil hanya $sisaBisaDibeli.";
            }
            return back()->with('error', $pesan);
        }

        $this->getCart()->add([
            'id' => $product->id,
            'name' => $product->name,
            'price' => $product->discount_price ?? $product->price,
            'quantity' => $requestedQty,
            'attributes' => [
                'image' => $product->image,
            ]
        ]);

        return redirect()->route('cart.index')->with('success', 'Produk berhasil ditambahkan ke keranjang!');
    }

    public function update(Request $request, $itemId)
    {
        $cartItem = $this->getCart()->get($itemId);
        
        if (!$cartItem) {
            return back()->with('error', 'Item tidak ditemukan di keranjang.');
        }

        $product = Product::find($cartItem->id);

        if (!$product) {
             $this->getCart()->remove($itemId);
             return back()->with('error', 'Produk tidak lagi tersedia.');
        }

        $requestedQty = (int) $request->quantity;

        if ($requestedQty > $product->stock) {
            return back()->with('error', "Maaf, stok hanya tersisa {$product->stock}. Tidak bisa update ke jumlah $requestedQty.");
        }

        $this->getCart()->update($itemId, [
            'quantity' => [
                'relative' => false,
                'value' => $requestedQty
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