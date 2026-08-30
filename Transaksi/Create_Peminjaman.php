<?php
session_start();
include "../koneksi.php";
include "../auth.php";
cekLogin();
include "header_Transaksi.php";

if (isset($_POST['insert'])) {
    $Nama_peminjam = $_POST['Nama_peminjam'];
    $Alamat = $_POST['Alamat'];
    $No_Telp = $_POST['No_Telp'];
    $Tgl_pinjam = $_POST['Tgl_pinjam'];
    $Id_buku = $_POST['Id_buku'];

    if ($Nama_peminjam != NULL && $Alamat != NULL && $No_Telp != NULL && $Tgl_pinjam != NULL && $Id_buku != NULL) {
        $sql = "INSERT INTO peminjaman VALUES (null,'$Nama_peminjam','$Alamat','$No_Telp','$Tgl_pinjam','$Id_buku')";
        $peminjaman = $koneksi->query($sql);
        if ($peminjaman) {
            echo "<script>alert('Data Peminjaman berhasil ditambahkan'); window.location.href='Peminjaman.php';</script>";
        } else {
            echo "<script>alert('Data Peminjaman gagal ditambahkan'); window.location.href='Create_Peminjaman.php';</script>";
        }
    }
}
?>
<div class="container">
    <div class="page-inner">
        <div class="page-header">
            <h3 class="fw-bold mb-3">Tambah Data</h3>
            <ul class="breadcrumbs mb-3">
                <li class="nav-home">
                    <a href="../index.php">
                        <i class="icon-home"></i>
                    </a>
                </li>
                <li class="separator">
                    <i class="icon-arrow-right"></i>
                </li>
                <li class="nav-item">
                    <a href="Peminjaman.php">Data Peminjaman</a>
                </li>
                <li class="separator">
                    <i class="icon-arrow-right"></i>
                </li>
                <li class="nav-item">
                    <a href="Create_Peminjaman.php">Tambah Data Peminjaman</a>
                </li>
            </ul>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">Tambah Data Peminjaman</div>
                    </div>
                    <div class="card-body">
                        <form action="Create_Peminjaman.php" method="post">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="Nama_Peminjaman">Nama Peminjam</label>
                                        <input type="text" name="Nama_peminjam" class="form-control" id="Nama_Peminjam"
                                            placeholder="Masukan Nama Peminjam " />
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="alamat">Alamat</label>
                                        <input type="text" name="Alamat" class="form-control" id="alamat"
                                            placeholder="Enter Alamat" />
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="no.telp">Nomor Telepon</label>
                                        <input type="text" name="No_Telp" class="form-control" id="no.telp"/>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="Tglpinjam">Tanggal Pinjam</label>
                                        <input type="date" name="Tgl_pinjam" class="form-control" id="Tglpinjam"
                                            placeholder="Masukan nomor" />
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="Id_buku">Buku Yang Dipinjam</label>
                                        <select class="form-select" name="Id_buku" id="Id_buku">
                                            <option value="11">Jujutsu Kaisen</option>
                                            <option value="13">Look Back</option>
                                            <option value="14">Little Prince</option>
                                        </select>
                                    </div>
                                </div>

                                <!-- Tombol -->
                                <div class="col-12 card-action mt-2">
                                    <button name="insert" class="btn btn-success">Submit</button>
                                    <a href="Peminjaman.php" class="btn btn-danger">Cancel</a>
                                </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>