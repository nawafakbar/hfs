@extends('layouts.user')
@section('title', 'Konfirmasi Checkout')
@section('content')

@php
    $subtotal = Cart::session(auth()->id())->getTotal();
@endphp

<div class="container py-5" style="margin-top: 100px;">
    <h2 class="mb-4">Konfirmasi Pesanan</h2>

    <div class="row">
        <div class="col-md-7">
            <div class="card mb-3">
                <div class="card-body text-start p-5" style="background-color: rgba(0, 0, 0, 0.04); border-radius: 12px;">
                    <h4 class="card-title">Alamat Pengiriman</h4>
                    <hr>
                    <p class="fw-bold mb-1">{{ $user->name }}</p>
                    <p class="mb-1">{{ $user->phone_number }}</p>
                    <p class="mb-3">
                        {{ $user->address }} <br>
                        Kec. {{ $user->kecamatan }}, {{ $user->kota }} <br>
                        {{ $user->provinsi }}
                    </p>
                    <a href="{{ route('profile.edit') }}" class="btn btn-sm btn-outline-secondary">
                        <i class="bi bi-pencil-square"></i> Ubah Alamat
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-5">
            <div class="card shadow-sm border-0">
                <div class="card-body p-4">
                    <h4 class="card-title">Ringkasan Pesanan</h4>
                    <hr>

                    <div class="d-flex justify-content-between mb-2">
                        <span>Subtotal</span>
                        <span class="fw-bold">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                    </div>

                    <div class="d-flex justify-content-between mb-2">
                        <span>Biaya Pengiriman</span>
                        <span id="ongkir_display" class="fw-bold text-muted">Rp 0</span>
                    </div>

                    <hr>

                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <span class="h5 mb-0">Total Bayar</span>
                        <span id="total_display" class="h4 mb-0 text-black fw-bold">
                            Rp {{ number_format($subtotal, 0, ',', '.') }}
                        </span>
                    </div>

                    <form action="{{ route('checkout.process') }}" method="POST">
                        @csrf
                        
                        <div class="mb-4">
                            <label class="form-label fw-bold">Pilih Layanan Pengiriman</label>
                            
                            <select class="form-select mb-3" name="shipping_cost" id="shipping_cost" required>
                                <option value="" disabled selected>-- Pilih Kurir --</option>
                                
                                <option value="0" data-courier="Ambil di Toko (Pickup)">
                                    Ambil di Toko (Pickup) - Rp 0
                                </option>

                                @if(!empty($ongkirResults))
                                    @foreach($ongkirResults as $result)
                                        <option value="{{ $result['cost'] }}" 
                                                data-courier="{{ $result['name'] }} - {{ $result['service'] }} ({{ $result['description'] }})">
                                            
                                            {{-- TAMPILAN DI DROPDOWN --}}
                                            {{ $result['code'] }} {{ $result['service'] }} - Rp {{ number_format($result['cost'], 0, ',', '.') }} 
                                            @if(!empty($result['etd'])) (Est: {{ $result['etd'] }}) @endif

                                        </option>
                                    @endforeach
                                @else
                                    <option value="" disabled>Tidak ada layanan pengiriman tersedia.</option>
                                @endif
                            </select>

                            <input type="hidden" name="shipping_method" id="shipping_method">
                            <span class="mt-4 text-danger">NOTE: Jika alamat mu masih berada di region padang silahkan pilih JNT EZ!</span>
                        </div>

                        <button type="submit" class="btn btn-secondary w-100 py-2 fw-bold" id="btn-bayar">
                            Lanjutkan Pembayaran <i class="bi bi-arrow-right"></i>
                        </button>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const selectOngkir = document.getElementById('shipping_cost');
        const displayOngkir = document.getElementById('ongkir_display');
        const displayTotal = document.getElementById('total_display');
        const inputMethod = document.getElementById('shipping_method');
        const subtotal = {{ $subtotal }};

        function formatRupiah(angka) {
            return 'Rp ' + angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        }

        selectOngkir.addEventListener('change', function () {
            const ongkirPrice = parseInt(this.value) || 0;
            
            const selectedOption = this.options[this.selectedIndex];
            const courierName = selectedOption.getAttribute('data-courier');
            
            displayOngkir.innerText = formatRupiah(ongkirPrice);
            const grandTotal = subtotal + ongkirPrice;
            displayTotal.innerText = formatRupiah(grandTotal);

            inputMethod.value = courierName;
        });
    });
</script>

@endsection