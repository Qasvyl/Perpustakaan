<?php
// Function untuk membuat kode barang otomatis(contoh: BR0001, BR0002, dst)

function generateKodePinjam($koneksi)
{

    // Ambil 1 data terakhir berdasarkan Id_peminjammanpaling besar

    $query = "SELECT Id_peminjaman FROM peminjaman ORDER BY Id_peminjaman DESC LIMIT 1";
    $result = mysqli_query($koneksi, $query);
    // Ambil hasil query dalam bentuk array
    $data = mysqli_fetch_assoc($result);
    // Jika belum ada data di tabel (data kosong)

    if (!$data) {
        // Maka mulai dari kode awal
        return "PJM001";
    }
    // Ambil angka dari id terakhir
// Contoh: BR0005 - ambil "0005"
    $number = (int) substr($data['Id_peminjaman'],3);


    // Tambahkan 1 angka
    $number++;

    // Gabungkan kembali dengan format:
// BR + angka 4 digit (pakai str_pad untukmenambahkan nol di depan)

    // Contoh: 6 - jadi 0006 - hasil: BR0006
    return "PJM" . str_pad($number, 3, "0", STR_PAD_LEFT);
}
function generateKodeKembali($koneksi)
{

    // Ambil 1 data terakhir berdasarkan Id_peminjammanpaling besar

    $query = "SELECT Id_pengembalian FROM peminjaman ORDER BY Id_pengembalian DESC LIMIT 1";
    $result = mysqli_query($koneksi, $query);
    // Ambil hasil query dalam bentuk array
    $data = mysqli_fetch_assoc($result);
    // Jika belum ada data di tabel (data kosong)

    if (!$data) {
        // Maka mulai dari kode awal
        return "KMB001";
    }
    // Ambil angka dari id terakhir
// Contoh: BR0005 - ambil "0005"
    $number = (int) substr($data['Id_pengembalian'],3);


    // Tambahkan 1 angka
    $number++;

    // Gabungkan kembali dengan format:
// BR + angka 4 digit (pakai str_pad untukmenambahkan nol di depan)

    // Contoh: 6 - jadi 0006 - hasil: BR0006
    return "KMB" . str_pad($number, 3, "0", STR_PAD_LEFT);
}
?>