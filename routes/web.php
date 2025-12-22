<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Mail;

// Import semua controller yang kita butuhkan
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\GoogleLoginController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\TestimonialController;
use App\Http\Controllers\MidtransCallbackController;
use App\Http\Controllers\ArticleController;

// Import controller admin
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\TestimonialController as AdminTestimonialController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\ArticleController as AdminArticleController;
use App\Http\Controllers\Admin\ReportController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/
// == RUTE UNTUK PENGUNJUNG BIASA (PUBLIC) ==
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/category/{category:slug}', [HomeController::class, 'index'])->name('home.category');
// Route::get('/shop', [ProductController::class, 'shop'])->name('shop.index');
// Route::get('/shop/category/{category:slug}', [ProductController::class, 'shop'])->name('shop.category');
Route::get('/product/{product:slug}', [ProductController::class, 'show'])->name('product.detail');
// Route::post('/midtrans/callback', [MidtransCallbackController::class, 'handle'])->name('midtrans.callback');
Route::get('/artikel', [ArticleController::class, 'index'])->name('articles.index');
Route::get('/artikel/{slug}', [ArticleController::class, 'show'])->name('article.show');

// == RUTE UNTUK GOOGLE LOGIN ==
Route::get('/auth/google/redirect', [GoogleLoginController::class, 'redirectToGoogle'])->name('google.redirect');
Route::get('/auth/google/callback', [GoogleLoginController::class, 'handleGoogleCallback'])->name('google.callback');


// == RUTE UNTUK USER YANG SUDAH LOGIN ==
// Breeze membuatkan route ini, biasanya mengarah ke /dashboard
Route::get('/dashboard', function () {
    // Arahkan admin ke admin dashboard, customer ke halaman lain
    if (auth()->user()->role === 'admin') {
        return redirect()->route('admin.dashboard');
    }
    // Ganti 'home' dengan route untuk dashboard customer jika ada
    return redirect()->route('home'); 
})->middleware(['auth', 'verified'])->name('dashboard');

//profile user
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Route Add to cart
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
    Route::post('/cart/update/{itemId}', [CartController::class, 'update'])->name('cart.update');
    Route::post('/cart/remove/{itemId}', [CartController::class, 'remove'])->name('cart.remove');

    // Rute untuk Checkout
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index')->middleware('profile.completed');
    Route::post('/checkout', [CheckoutController::class, 'process'])->name('checkout.process')->middleware('profile.completed');
    
    // RUTE BARU UNTUK HALAMAN PEMBAYARAN
    Route::get('/checkout/payment/{order:invoice_number}', [CheckoutController::class, 'payment'])->name('checkout.payment');
    Route::get('/checkout/success', [CheckoutController::class, 'success'])->name('checkout.success');

    // Halaman untuk menampilkan form upload
    Route::get('/orders/{order:invoice_number}/upload', [App\Http\Controllers\OrderController::class, 'showUploadForm'])->name('orders.uploadForm');
    // Route untuk memproses file upload
    Route::post('/orders/{order:invoice_number}/upload', [App\Http\Controllers\OrderController::class, 'storeUpload'])->name('orders.storeUpload');

    // == RUTE UNTUK PESANAN SAYA (CUSTOMER) ==
    Route::get('/my-orders', [App\Http\Controllers\OrderController::class, 'index'])->name('orders.index');
    Route::get('/my-orders/{order:invoice_number}', [App\Http\Controllers\OrderController::class, 'show'])->name('orders.show');

    // Cetak Nota
    Route::get('/orders/{order:invoice_number}/print', [App\Http\Controllers\OrderController::class, 'print'])->name('orders.print');

    // == RUTE UNTUK TESTIMONI ==
    // Halaman untuk menampilkan form ulasan
    Route::get('/review/create/{product}', [App\Http\Controllers\TestimonialController::class, 'create'])->name('testimonial.create');
    // Route untuk menyimpan ulasan baru
    Route::post('/review/store', [App\Http\Controllers\TestimonialController::class, 'store'])->name('testimonial.store');
});

// == GRUP RUTE KHUSUS ADMIN ==
Route::middleware(['auth', 'is_admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::resource('categories', CategoryController::class);
    // Kita gunakan alias 'AdminProductController' agar tidak bentrok
    Route::resource('products', AdminProductController::class); 
    Route::resource('orders', OrderController::class)->only(['index', 'show', 'update', 'destroy', 'create', 'store']);
    // Route::get('/orders/create', [App\Http\Controllers\Admin\OrderController::class, 'create'])->name('orders.create');
    // Route::post('/orders', [App\Http\Controllers\Admin\OrderController::class, 'store'])->name('orders.store');
    Route::resource('testimonials', AdminTestimonialController::class)->only(['index', 'update', 'destroy']);
    Route::resource('customers', CustomerController::class)->except(['create', 'store']);
    Route::resource('articles', AdminArticleController::class);
    // Halaman Laporan Keuangan
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/pdf', [ReportController::class, 'exportPdf'])->name('reports.pdf');
    Route::get('/reports/excel', [ReportController::class, 'exportExcel'])->name('reports.excel');  
});


Route::get('/test-email', function () {
    try {
        Mail::raw('Halo bro, ini tes email dari Hostinger ke Gmail. Kalau ini masuk, berarti server aman.', function ($message) {
            // Ganti dengan email Gmail tujuanmu
            $message->to('nwfakbart@gmail.com')
                    ->subject('Tes Koneksi Email Laravel');
        });
        return 'Email berhasil dikirim oleh Laravel. Cek inbox/spam Gmail sekarang.';
    } catch (\Exception $e) {
        return 'Gagal kirim: ' . $e->getMessage();
    }
});


// == RUTE BAWAAN DARI LARAVEL BREEZE ==
// Ini akan menangani semua route seperti /login, /register, /logout, /profile, dll.
require __DIR__.'/auth.php';
