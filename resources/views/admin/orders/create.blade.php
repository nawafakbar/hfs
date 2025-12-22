@extends('layouts.admin')
@section('page-title', 'Tambah Pesanan Manual')

@section('content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title">Tambah Pesanan Baru (Offline/Warehouse)</h2>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <form action="{{ route('admin.orders.store') }}" method="POST" class="card">
            @csrf
            <div class="card-body">
                
                {{-- Data Pembeli --}}
                <div class="mb-4">
                    <label class="form-label required">Nama Pembeli</label>
                    <input type="text" name="customer_name" class="form-control" placeholder="Nama pelanggan yang datang..." required>
                </div>

                <div class="hr-text">Detail Produk</div>

                {{-- Tabel Input Produk Dinamis --}}
                <table class="table table-vcenter" id="product-table">
                    <thead>
                        <tr>
                            <th style="width: 50%">Produk</th>
                            <th style="width: 20%">Stok</th>
                            <th style="width: 20%">Jumlah Beli</th>
                            <th style="width: 10%"></th>
                        </tr>
                    </thead>
                    <tbody id="product-list">
                        {{-- Baris Pertama Default --}}
                        <tr>
                            <td>
                                <select name="products[]" class="form-select product-select" required onchange="updateStock(this)">
                                    <option value="" data-stock="0" selected disabled>-- Pilih Produk --</option>
                                    @foreach($products as $product)
                                        <option value="{{ $product->id }}" data-stock="{{ $product->stock }}">
                                            {{ $product->name }} - Rp {{ number_format($product->price, 0, ',', '.') }}
                                        </option>
                                    @endforeach
                                </select>
                            </td>
                            <td>
                                <span class="badge bg-secondary stock-display">0</span>
                            </td>
                            <td>
                                <input type="number" name="quantities[]" class="form-control" min="1" value="1" required>
                            </td>
                            <td>
                                <button type="button" class="btn btn-danger btn-icon" onclick="removeRow(this)" disabled>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-trash" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 7l16 0" /><path d="M10 11l0 6" /><path d="M14 11l0 6" /><path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" /><path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" /></svg>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>

                <div class="mt-3">
                    <button type="button" class="btn btn-outline-primary" onclick="addRow()">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-plus" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 5l0 14" /><path d="M5 12l14 0" /></svg>
                        Tambah Baris Produk
                    </button>
                </div>

            </div>
            <div class="card-footer text-end">
                <a href="{{ route('admin.orders.index') }}" class="btn btn-link link-secondary">Batal</a>
                <button type="submit" class="btn btn-primary ms-auto">Buat Pesanan & Selesaikan</button>
            </div>
        </form>
    </div>
</div>

<script>
    // Fungsi Update Tampilan Stok saat produk dipilih
    function updateStock(selectElement) {
        const selectedOption = selectElement.options[selectElement.selectedIndex];
        const stock = selectedOption.getAttribute('data-stock');
        const row = selectElement.closest('tr');
        const stockDisplay = row.querySelector('.stock-display');
        const quantityInput = row.querySelector('input[type="number"]');
        
        stockDisplay.textContent = stock;
        
        // Update max input agar admin tidak input lebih dari stok
        quantityInput.setAttribute('max', stock);
        
        // Ubah warna badge stok
        if(parseInt(stock) == 0) {
            stockDisplay.className = 'badge bg-danger stock-display';
        } else if (parseInt(stock) < 5) {
            stockDisplay.className = 'badge bg-warning stock-display';
        } else {
            stockDisplay.className = 'badge bg-success stock-display';
        }
    }

    // Fungsi Tambah Baris
    function addRow() {
        const table = document.getElementById('product-list');
        const newRow = table.rows[0].cloneNode(true);
        
        // Reset nilai input di baris baru
        newRow.querySelector('select').value = "";
        newRow.querySelector('.stock-display').textContent = "0";
        newRow.querySelector('input').value = "1";
        newRow.querySelector('button').disabled = false; // Aktifkan tombol hapus

        table.appendChild(newRow);
    }

    // Fungsi Hapus Baris
    function removeRow(button) {
        const row = button.closest('tr');
        // Jangan hapus jika cuma sisa 1 baris
        if (document.querySelectorAll('#product-list tr').length > 1) {
            row.remove();
        }
    }
</script>
@endsection