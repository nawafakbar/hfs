@extends('layouts.profile')

@section('title', 'Profil Saya')

@section('content')
    <div>
        <h2 class="mb-1">Profil Saya</h2>

        @if (session('warning'))
            <div class="alert alert-warning">{{ session('warning') }}</div>
        @endif

        <p class="text-muted">
            Kelola informasi profil anda untuk mengontrol, melindungi, dan mengamankan akun.
        </p>
    </div>

    <hr class="my-4">

    @if (session('status') === 'profile-updated')
        <div class="alert alert-success">Profil berhasil diperbarui.</div>
    @endif

    <div class="row">
        <div class="col-md-8">

            <form method="post" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                @csrf
                @method('patch')

                {{-- NAMA --}}
                <div class="mb-3 row">
                    <label class="col-sm-3 col-form-label">Nama Lengkap</label>
                    <div class="col-sm-9">
                        <input type="text" name="name" class="form-control"
                            value="{{ old('name', $user->name) }}" required>
                    </div>
                </div>

                {{-- EMAIL --}}
                <div class="mb-3 row">
                    <label class="col-sm-3 col-form-label">Email</label>
                    <div class="col-sm-9">
                        <input type="email" name="email" class="form-control"
                            value="{{ old('email', $user->email) }}" required>
                    </div>
                </div>

                {{-- NO TELEPON --}}
                <div class="mb-3 row">
                    <label class="col-sm-3 col-form-label">Nomor Telepon</label>
                    <div class="col-sm-9">
                        <input type="text" name="phone_number" class="form-control"
                            value="{{ old('phone_number', $user->phone_number) }}">
                    </div>
                </div>

                {{-- PROVINSI --}}
                <div class="mb-3 row">
                    <label for="provinsi" class="col-sm-3 col-form-label">Provinsi</label>
                    <div class="col-sm-9">
                        <select name="provinsi" id="provinsi" class="form-control" required>
                            <option value="">-- Pilih Provinsi --</option>
                        </select>
                    </div>
                </div>

                {{-- KOTA --}}
                <div class="mb-3 row">
                    <label for="kota" class="col-sm-3 col-form-label">Kota / Kabupaten</label>
                    <div class="col-sm-9">
                        <select name="kota" id="kota" class="form-control" required>
                            <option value="">-- Pilih Kota / Kabupaten --</option>
                        </select>
                    </div>
                </div>

                {{-- KECAMATAN --}}
                <div class="mb-3 row">
                    <label for="kecamatan" class="col-sm-3 col-form-label">Kecamatan</label>
                    <div class="col-sm-9">
                        <select name="kecamatan" id="kecamatan" class="form-control" required>
                            <option value="">-- Pilih Kecamatan --</option>
                        </select>
                    </div>
                </div>

                {{-- INPUT HIDDEN MENYIMPAN NAMA WILAYAH --}}
                <input type="hidden" name="provinsi_name" id="provinsi_name" value="{{ $user->provinsi }}">
                <input type="hidden" name="kota_name" id="kota_name" value="{{ $user->kota }}">
                <input type="hidden" name="kecamatan_name" id="kecamatan_name" value="{{ $user->kecamatan }}">

                {{-- KODE POS --}}
                <div class="mb-3 row">
                    <label class="col-sm-3 col-form-label">Kode Pos</label>
                    <div class="col-sm-9">
                        <input type="text" name="kode_pos" class="form-control"
                            value="{{ old('kode_pos', $user->kode_pos) }}">
                    </div>
                </div>

                {{-- ALAMAT LENGKAP --}}
                <div class="mb-3 row">
                    <label class="col-sm-3 col-form-label">Alamat Lengkap</label>
                    <div class="col-sm-9">
                        <textarea name="address" class="form-control" rows="3" required>{{ old('address', $user->address) }}</textarea>
                    </div>
                </div>

                <input type="file" id="photo-upload" name="profile_photo" class="d-none">

                <div class="row mt-4">
                    <div class="col-sm-9 offset-sm-3">
                        <button type="submit" class="btn btn-dark">Simpan Perubahan</button>
                    </div>
                </div>

            </form>
        </div>

        {{-- FOTO PROFIL --}}
        <div class="col-md-4">
            <div class="text-center mt-3">
                <img src="{{ $user->profile_photo_path 
                        ? asset('storage/' . $user->profile_photo_path) 
                        : 'https://ui-avatars.com/api/?name='.urlencode($user->name).'&background=random' }}"
                    class="img-thumbnail rounded-circle"
                    style="width:150px; height:150px; object-fit:cover;">

                <label for="photo-upload" class="btn btn-outline-dark btn-sm mt-3">Pilih Gambar</label>
            </div>
        </div>
    </div>

@endsection

<script>
document.addEventListener("DOMContentLoaded", function () {

    let provinsiSelect = document.getElementById("provinsi");
    let kotaSelect = document.getElementById("kota");
    let kecamatanSelect = document.getElementById("kecamatan");

    let provinsiName = document.getElementById("provinsi_name");
    let kotaName = document.getElementById("kota_name");
    let kecamatanName = document.getElementById("kecamatan_name");

    const userProv = "{{ $user->provinsi }}";
    const userKota = "{{ $user->kota }}";
    const userKecamatan = "{{ $user->kecamatan }}";

    // LOAD PROVINSI
    fetch("https://www.emsifa.com/api-wilayah-indonesia/api/provinces.json")
    .then(res => res.json())
    .then(data => {
        data.forEach(prov => {
            let option = new Option(prov.name, prov.id);
            provinsiSelect.add(option);
        });

        if (userProv) {
            let selected = [...provinsiSelect.options].find(o => o.text === userProv);
            if (selected) {
                selected.selected = true;
                provinsiName.value = selected.text;
                loadKota(selected.value);
            }
        }
    });

    // LOAD KOTA
    function loadKota(provID) {
        kotaSelect.innerHTML = `<option value="">-- Pilih Kota / Kabupaten --</option>`;
        kecamatanSelect.innerHTML = `<option value="">-- Pilih Kecamatan --</option>`;

        fetch(`https://www.emsifa.com/api-wilayah-indonesia/api/regencies/${provID}.json`)
        .then(res => res.json())
        .then(data => {
            data.forEach(kota => {
                let option = new Option(kota.name, kota.id);
                kotaSelect.add(option);
            });

            if (userKota) {
                let selected = [...kotaSelect.options].find(o => o.text === userKota);
                if (selected) {
                    selected.selected = true;
                    kotaName.value = selected.text;
                    loadKecamatan(selected.value);
                }
            }
        });
    }

    // LOAD KECAMATAN
    function loadKecamatan(kotaID) {
        kecamatanSelect.innerHTML = `<option value="">-- Pilih Kecamatan --</option>`;

        fetch(`https://www.emsifa.com/api-wilayah-indonesia/api/districts/${kotaID}.json`)
        .then(res => res.json())
        .then(data => {
            data.forEach(kec => {
                let option = new Option(kec.name, kec.id);
                kecamatanSelect.add(option);
            });

            if (userKecamatan) {
                let selected = [...kecamatanSelect.options].find(o => o.text === userKecamatan);
                if (selected) {
                    selected.selected = true;
                    kecamatanName.value = selected.text;
                }
            }
        });
    }

    // UPDATE HIDDEN INPUT SAAT DIPILIH
    provinsiSelect.addEventListener("change", function () {
        provinsiName.value = this.options[this.selectedIndex].text;
        loadKota(this.value);
    });

    kotaSelect.addEventListener("change", function () {
        kotaName.value = this.options[this.selectedIndex].text;
        loadKecamatan(this.value);
    });

    kecamatanSelect.addEventListener("change", function () {
        kecamatanName.value = this.options[this.selectedIndex].text;
    });

});
</script>
