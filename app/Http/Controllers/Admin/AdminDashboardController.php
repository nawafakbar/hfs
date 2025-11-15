<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\User;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    public function index()
    {
        // 1. Total Pendapatan
        $totalRevenue = Order::where('status', 'completed')->sum('total_amount');

        // 2. Jumlah Pesanan Baru (misal statusnya 'paid' atau 'packaging')
        $newOrdersCount = Order::whereIn('status', ['paid', 'packaging', 'waiting_confirmation'])->count();

        // 3. Jumlah Pelanggan
        $customersCount = User::where('role', 'customer')->count();

        // 4. Pesanan Terbaru (5 terakhir)
        $recentOrders = Order::with('user')->latest()->take(5)->get();

        // 5. Produk Terlaris
        $bestSellingProducts = DB::table('order_items')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->select('products.name', DB::raw('SUM(order_items.quantity) as total_sold'))
            ->groupBy('products.name')
            ->orderBy('total_sold', 'desc')
            ->take(5)
            ->get();

        // Kirim semua data ke view
        return view('admin.dashboard', compact(
            'totalRevenue', 
            'newOrdersCount', 
            'customersCount',
            'recentOrders',
            'bestSellingProducts'
        ));
    }
}