<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Carbon\Carbon;
use PDF; // Facade DomPDF
use Maatwebsite\Excel\Facades\Excel; // Facade Excel
use App\Exports\RevenueExport; // Kita akan buat file ini nanti

class ReportController extends Controller
{
    /**
     * Logic utama untuk filter data laporan
     */
    private function getFilteredOrders(Request $request)
    {
        $query = Order::query();

        // Hanya ambil order yang sudah sukses (paid / completed)
        // Sesuaikan status ini dengan sistem kamu
        $query->whereIn('status', ['paid', 'shipping', 'completed']);

        // Filter Berdasarkan Periode
        if ($request->periode == 'weekly') {
            $query->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
        } elseif ($request->periode == 'monthly') {
            $query->whereMonth('created_at', Carbon::now()->month)
                  ->whereYear('created_at', Carbon::now()->year);
        } elseif ($request->periode == 'yearly') {
            $query->whereYear('created_at', Carbon::now()->year);
        } elseif ($request->start_date && $request->end_date) {
            // Filter Custom Tanggal
            $query->whereBetween('created_at', [$request->start_date, $request->end_date]);
        }

        return $query->latest()->get();
    }

    public function index(Request $request)
    {
        $orders = [];
        $totalRevenue = 0;

        // Jika ada request filter, baru kita cari datanya
        if ($request->has('filter')) {
            $orders = $this->getFilteredOrders($request);
            $totalRevenue = $orders->sum('total_amount');
        }

        return view('admin.reports.index', compact('orders', 'totalRevenue'));
    }

    public function exportPdf(Request $request)
    {
        $orders = $this->getFilteredOrders($request);
        $totalRevenue = $orders->sum('total_amount');
        $periode = $request->periode ?? 'Custom';

        $pdf = PDF::loadView('admin.reports.pdf', compact('orders', 'totalRevenue', 'periode'));
        
        return $pdf->download('laporan-keuangan-bgd.pdf');
    }

    public function exportExcel(Request $request)
    {
        // Menggunakan Class Export terpisah agar rapi
        return Excel::download(new RevenueExport($this->getFilteredOrders($request)), 'laporan-keuangan-bgd.xlsx');
    }
}