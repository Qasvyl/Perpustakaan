<?php
function generateKodePetugas($koneksi)
{

    // Ambil 1 data terakhir berdasarkan Id_peminjammanpaling besar

    $query = "SELECT Id_petugas FROM petugas ORDER BY Id_petugas DESC LIMIT 1";
    $result = mysqli_query($koneksi, $query);
    // Ambil hasil query dalam bentuk array
    $data = mysqli_fetch_assoc($result);
    // Jika belum ada data di tabel (data kosong)

    if (!$data) {
        // Maka mulai dari kode awal
        return "PT01";
    }
    // Ambil angka dari id terakhir
// Contoh: BR0005 - ambil "0005"
    $number = (int) substr($data['Id_petugas'],2);


    // Tambahkan 1 angka
    $number++;

    // Gabungkan kembali dengan format:
// BR + angka 4 digit (pakai str_pad untukmenambahkan nol di depan)

    // Contoh: 6 - jadi 0006 - hasil: BR0006
    return "PT" . str_pad($number, 2, "0", STR_PAD_LEFT);
}
?>