<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use App\Models\Order;
use App\Models\OrderItem;
use Cart;

class CheckoutController extends Controller
{
    // CONFIG SAYA HARDCODE BIAR GAK ADA ISU CACHE LAGI
    private $apiKey = 'Ytw5qRf84a9615933dd0698eBJNsOede'; 
    private $baseUrl = 'https://rajaongkir.komerce.id/api/v1';
    private $originId = 4031; // Nanggalo (ID Toko)

    private function getCart()
    {
        $userId = auth()->id();
        return Cart::session($userId);
    }

    /**
     * FUNGSI PENCARI ID (Mengikuti Pola Tutorial: Prov -> Kota -> Kec)
     */
    private function findDestinationId($userProvinsi, $userKota, $userKecamatan)
    {
        $headers = [
            'Accept' => 'application/json',
            'key' => $this->apiKey
        ];

        // ---------------------------------------------------------
        // TAHAP 1: Cari ID Provinsi ("JAWA BARAT" -> ID?)
        // ---------------------------------------------------------
        $responseProv = Http::withHeaders($headers)->get($this->baseUrl . '/destination/province');
        $provId = null;

        if ($responseProv->successful()) {
            foreach ($responseProv->json()['data'] as $prov) {
                // Pencocokan nama provinsi (Case Insensitive)
                if (stripos($prov['name'], $userProvinsi) !== false) {
                    $provId = $prov['id'];
                    break;
                }
            }
        }

        if (!$provId) return null; // Provinsi gak ketemu, stop.

        // ---------------------------------------------------------
        // TAHAP 2: Cari ID Kota ("KABUPATEN PURWAKARTA" -> ID?)
        // URL Tutorial: /destination/city/{provinceId}
        // ---------------------------------------------------------
        $responseCity = Http::withHeaders($headers)->get($this->baseUrl . "/destination/city/{$provId}");
        $cityId = null;
        
        // Bersihkan "KABUPATEN" atau "KOTA" dari string user biar cocok dengan API "Purwakarta"
        $cleanKotaUser = trim(str_ireplace(['KABUPATEN', 'KOTA'], '', $userKota));

        if ($responseCity->successful()) {
            foreach ($responseCity->json()['data'] as $city) {
                if (stripos($city['name'], $cleanKotaUser) !== false) {
                    $cityId = $city['id'];
                    break;
                }
            }
        }

        if (!$cityId) return null; // Kota gak ketemu, stop.

        // ---------------------------------------------------------
        // TAHAP 3: Cari ID Kecamatan ("CIBATU" -> ID?)
        // URL Tutorial: /destination/district/{cityId}
        // ---------------------------------------------------------
        $responseDistrict = Http::withHeaders($headers)->get($this->baseUrl . "/destination/district/{$cityId}");
        $districtId = null;

        if ($responseDistrict->successful()) {
            foreach ($responseDistrict->json()['data'] as $dist) {
                if (stripos($dist['name'], $userKecamatan) !== false) {
                    $districtId = $dist['id'];
                    break;
                }
            }
        }

        return $districtId; // Akhirnya ketemu ID Kecamatan (misal: 3855)
    }

    public function index()
    {
        if ($this->getCart()->getTotal() <= 0) return redirect()->route('cart.index');

        $user = Auth::user();
        if (!$user->kecamatan || !$user->kota || !$user->provinsi || !$user->address) {
            return redirect()->route('profile.edit')->with('error', 'Mohon lengkapi Provinsi, Kota, Kecamatan, dan Alamat.');
        }

        $ongkirResults = [];

        try {
            // Panggil fungsi pencari ID berjenjang di atas
            $destinationId = $this->findDestinationId($user->provinsi, $user->kota, $user->kecamatan);

            if ($destinationId) {
                
                // Hitung berat total
                $totalBerat = 0;
                foreach ($this->getCart()->getContent() as $item) {
                    $beratItem = $item->associatedModel->weight ?? 250; 
                    $totalBerat += ($beratItem * $item->quantity);
                }

                // ---------------------------------------------------------
                // TAHAP 4: Hitung Ongkir (Sesuai Tutorial pakai asForm)
                // ---------------------------------------------------------
                $kurirPilihan = 'jne:sicepat:jnt:pos'; // Default (Luar Kota)

                // Cek apakah Kota user mengandung kata "PADANG" (Case Insensitive)
                // Jadi "KOTA PADANG", "Padang", "padang" akan terbaca
                if (stripos($user->kota, 'PADANG') !== false) {
                    $kurirPilihan = 'jnt'; // Khusus Padang cuma J&T
                }
                
                // ---------------------------------------------------------
                // TAHAP 4: Hitung Ongkir
                // ---------------------------------------------------------
                $responseCost = Http::asForm()->withHeaders([
                    'Accept' => 'application/json',
                    'key'    => $this->apiKey,
                ])->post($this->baseUrl . '/calculate/domestic-cost', [
                    'origin'      => $this->originId, 
                    'destination' => $destinationId, 
                    'weight'      => $totalBerat,
                    
                    // Ganti string hardcode tadi dengan variabel $kurirPilihan
                    'courier'     => $kurirPilihan, 
                ]);

                if ($responseCost->successful()) {
                    $dataMentah = $responseCost->json()['data'] ?? [];

                    // --- OPSIONAL: FILTER KHUSUS SERVICE "EZ" ---
                    // Kalau ternyata J&T memunculkan "ECO" atau "SUPER", dan kamu STRICT cuma mau "EZ"
                    // Aktifkan kode di bawah ini:
                    /*
                    $ongkirResults = array_filter($dataMentah, function($item) {
                        // Kalau kurirnya J&T, pastikan servicenya "EZ"
                        if ($item['code'] == 'jnt') {
                            return $item['service'] == 'EZ';
                        }
                        return true; // Kurir lain lolos
                    });
                    */
                    
                    // Kalau gak mau ribet filter service, pakai data mentah aja (biasanya sesama kota emang cuma EZ)
                    $ongkirResults = $dataMentah;
                }
            } else {
                // Debugging: Jika ID tidak ketemu, cek log laravel
                Log::error("Lokasi gagal dicocokkan untuk: " . $user->provinsi . " - " . $user->kota . " - " . $user->kecamatan);
            }

        } catch (\Exception $e) {
            Log::error("Checkout Error: " . $e->getMessage());
        }

        $carts = $this->getCart()->getContent();
        return view('user.checkout', compact('user', 'carts', 'ongkirResults'));
    }

    // --- BAGIAN PROCESS, PAYMENT, SUCCESS TETAP SAMA ---
    public function process(Request $request)
    {
        $total_belanja = $this->getCart()->getTotal();
        if ($total_belanja <= 0) return redirect()->route('cart.index');

        $user = auth()->user();
        $ongkir = (int) $request->shipping_cost; 
        
        $shipping_method = $request->shipping_method;
        if(empty($shipping_method)) {
            $shipping_method = ($ongkir == 0) ? 'Pickup (Ambil Sendiri)' : 'Ekspedisi';
        }

        $full_address = $user->address . ', ' . $user->kecamatan . ', ' . $user->kota . ', ' . $user->provinsi;
        $total_amount = $total_belanja + $ongkir;

        $order = Order::create([
            'user_id' => $user->id,
            'invoice_number' => 'INV-' . time() . '-' . $user->id,
            'subtotal' => $total_belanja,
            'shipping_cost' => $ongkir,
            'total_amount' => $total_amount,
            'shipping_method' => $shipping_method,
            'shipping_address' => $full_address,
            'status' => 'pending',
        ]);

        foreach ($this->getCart()->getContent() as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item->id,
                'quantity' => $item->quantity,
                'price' => $item->price,
            ]);
        }

        $this->getCart()->clear();
        return redirect()->route('checkout.payment', $order->invoice_number);
    }

    public function payment(Order $order) {
        if ($order->user_id !== auth()->id()) abort(403);
        return view('user.payment', compact('order'));
    }

    public function success(Request $request) {
        $invoice = $request->query('invoice');
        $order = Order::where('invoice_number', $invoice)->first();
        if(!$order) return redirect('/');
        return view('user.checkout_success', compact('order'));
    }
}