<?php

// Dibuat berdasarkan file: zona_ongkir_padang.md
// Titik Awal: Kecamatan NANGGALO

return [

    /*
    | Data Zona Ongkir Kota Padang
    | Format: "NAMA KECAMATAN" => HargaOngkir
    | WAJIB FULL KAPITAL karena case-sensitive
    */
    'zona_padang' => [
        // ZONA 1 (Rp 5.000)
        "NANGGALO"             => 5000,
        "PADANG UTARA"         => 5000,
        "KURANJI"              => 5000,

        // ZONA 2 (Rp 10.000)
        "PADANG BARAT"         => 10000,
        "PADANG TIMUR"         => 10000,
        "PAUH"                 => 10000,
        "LUBUK BEGALUNG"       => 10000,

        // ZONA 3 (Rp 15.000)
        "PADANG SELATAN"       => 15000,
        "KOTO TANGAH"          => 15000,
        "LUBUK KILANGAN"       => 15000,
        "BUNGUS TELUK KABUNG"  => 15000,
    ],

    /*
    | Daftar Kecamatan (FULL KAPITAL)
    */
    'daftar_kecamatan' => [
        "NANGGALO",
        "PADANG UTARA",
        "KURANJI",
        "PADANG BARAT",
        "PADANG TIMUR",
        "PAUH",
        "LUBUK BEGALUNG",
        "PADANG SELATAN",
        "KOTO TANGAH",
        "LUBUK KILANGAN",
        "BUNGUS TELUK KABUNG",
    ],

];
