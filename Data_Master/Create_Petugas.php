<?php
session_start();
include "../koneksi.php";
include "../auth.php";
cekAdmin();
include "header_datatable.php";
include "kode_petugas.php";
$kodepetugas = generateKodePetugas($koneksi);

if (isset($_POST['insert'])) {
    $Id_petugas = $_POST['Id_petugas'];
    $Nama = $_POST['Nama'];
    $Password = md5($_POST['Password']);
    $JenisKelamin = $_POST['JenisKelamin'];
    $Role = $_POST['Role'];

    if ($Id_petugas != NULL && $Nama != NULL && $Password != NULL && $JenisKelamin != NULL && $Role != NULL) {
        $sql = "INSERT INTO petugas VALUES ('$kodepetugas','$Nama','$Password','$JenisKelamin','$Role')";
        $petugas = $koneksi->query($sql);
        if ($petugas) {
            echo "<script>alert('Data Petugas berhasil ditambahkan'); window.location.href='Data_Petugas.php';</script>";
        } else {
            echo "<script>alert('Data Petugas gagal ditambahkan'); window.location.href='Create_Petugas.php';</script>";
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
                    <a href="Data_Petugas.php">Data Petugas</a>
                </li>
                <li class="separator">
                    <i class="icon-arrow-right"></i>
                </li>
                <li class="nav-item">
                    <a href="Create_Petugas.php">Tambah Data Petugas</a>
                </li>
            </ul>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">Tambah Data Petugas</div>
                    </div>
                    <div class="card-body">
                        <form action="Create_Petugas.php" method="post">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="nama">ID Petugas</label>
                                        <input type="text" name="Id_petugas" class="form-control" id="nama" value="<?= $kodepetugas?>" readonly>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="nama">Nama</label>
                                        <input type="text" name="Nama" class="form-control" id="nama"
                                            placeholder="Masukan Nama Petugas" />
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="password">Password</label>
                                        <input type="password" name="Password" class="form-control" id="password"
                                            placeholder="Password" />
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="jenisKelamin">Jenis Kelamin</label>
                                        <select class="form-select" name="JenisKelamin" id="jenisKelamin">
                                            <option value="Pria">Pria</option>
                                            <option value="Wanita">Wanita</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="role">Role</label>
                                        <select class="form-select" name="Role" id="role">
                                            <option value="Admin">Admin</option>
                                            <option value="Petugas">Petugas</option>
                                        </select>
                                    </div>
                                </div>

                                <!-- Tombol -->
                                <div class="col-12 card-action mt-2">
                                    <button name="insert" class="btn btn-success">Submit</button>
                                    <a href="Data_Petugas.php" class="btn btn-danger">Cancel</a>
                                </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>