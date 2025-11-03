<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\Testimonial;
use Illuminate\Http\Request;

class TestimonialController extends Controller
{
    /**
     * Menampilkan form untuk membuat ulasan baru.
     */
    public function create(Product $product)
    {
        $userId = auth()->id();

        // 1. Cek apakah user pernah membeli produk ini (status paid atau completed)
        $hasPurchased = Order::where('user_id', $userId)
            ->whereIn('status', ['paid', 'completed'])
            ->whereHas('orderItems', function ($query) use ($product) {
                $query->where('product_id', $product->id);
            })
            ->exists();

        // 2. Cek apakah user sudah pernah mereview produk ini
        $hasReviewed = Testimonial::where('user_id', $userId)
            ->where('product_id', $product->id)
            ->exists();

        // Jika belum pernah beli, tendang
        if (!$hasPurchased) {
            return redirect()->route('orders.index')->withErrors('Anda hanya dapat memberi ulasan untuk produk yang sudah Anda beli.');
        }

        // Jika sudah pernah review, tendang
        if ($hasReviewed) {
            return redirect()->route('orders.index')->withErrors('Anda sudah pernah memberi ulasan untuk produk ini.');
        }

        // Jika lolos, tampilkan form
        return view('user.testimonials.create', compact('product'));
    }

    /**
     * Menyimpan ulasan baru ke database.
     */
    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|max:1000',
        ]);

        $userId = auth()->id();

        // Cek lagi (demi keamanan) apakah user sudah pernah mereview
        $hasReviewed = Testimonial::where('user_id', $userId)
            ->where('product_id', $request->product_id)
            ->exists();
        
        if ($hasReviewed) {
            return redirect()->route('orders.index')->withErrors('Anda sudah pernah memberi ulasan untuk produk ini.');
        }

        // Buat testimoni baru
        Testimonial::create([
            'user_id' => $userId,
            'product_id' => $request->product_id,
            'rating' => $request->rating,
            'comment' => $request->comment,
            'status' => 'pending', // Perlu persetujuan admin
        ]);

        return redirect()->route('orders.index')->with('success', 'Terima kasih! Ulasan Anda telah dikirim dan menunggu persetujuan admin.');
    }
}