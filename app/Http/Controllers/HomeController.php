<?php
namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Category $category = null)
    {
        // Ambil semua kategori untuk ditampilkan di tombol filter
        $categories = Category::latest()->get();
        
        // Mulai query untuk produk
        $productsQuery = Product::query();

        // Jika ada objek $category yang dikirim dari URL, filter produknya
        if ($category) {
            $productsQuery->where('category_id', $category->id);
        }

        // Ambil hasil produk dengan paginasi (misalnya 12 produk per halaman)
        $products = $productsQuery->latest()->paginate(12);

        // Kirim semua data yang dibutuhkan ke view
        return view('user.Index', [
            'products' => $products,
            'categories' => $categories,
            'activeCategory' => $category, // Variabel untuk menandai kategori mana yang aktif
        ]);
    }
}