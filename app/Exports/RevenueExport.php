<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class RevenueExport implements FromCollection, WithHeadings, WithMapping
{
    protected $orders;

    public function __construct($orders)
    {
        $this->orders = $orders;
    }

    public function collection()
    {
        return $this->orders;
    }

    // Header Kolom di Excel
    public function headings(): array
    {
        return [
            'Invoice',
            'Nama Pelanggan',
            'Tanggal Order',
            'Status',
            'Total Pembayaran',
        ];
    }

    // Data per baris
    public function map($order): array
    {
        return [
            $order->invoice_number,
            $order->user->name ?? 'Guest',
            $order->created_at->format('d M Y'),
            ucfirst($order->status),
            $order->total_amount,
        ];
    }
}