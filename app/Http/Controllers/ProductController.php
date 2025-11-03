<?php
namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Menampilkan halaman detail produk.
     * Laravel akan otomatis mencari produk berdasarkan slug dari URL.
     */
    public function shop(Category $category = null)
    {
        $categories = Category::latest()->get();
        $products = Product::query();

        if ($category) {
            $products->where('category_id', $category->id);
        }

        return view('user.shop', [
            'products' => $products->latest()->paginate(12),
            'categories' => $categories,
            'activeCategory' => $category,
        ]);
    }

    /**
     * Menampilkan halaman detail produk.
     */
    public function show(Product $product)
    {
        // Load relasi kategori (ini mungkin sudah ada)
        $product->load('category');

        // ==========================================================
        // PERUBAHAN UTAMA DI SINI
        // Kita perlu eager load relasi testimonials, TAPI:
        // 1. Hanya yang statusnya 'approved'.
        // 2. Kita juga butuh data 'user' dari testimonial tsb (N+1 fix).
        // 3. Urutkan dari yang terbaru.
        // ==========================================================
        $product->load(['testimonials' => function ($query) {
            $query->where('status', 'approved')
                  ->with('user') // Eager load data user yang memberi review
                  ->latest();
        }]);

        // Kirim data produk yang sudah lengkap ke view
        return view('user.detail_product', compact('product'));
    }
}