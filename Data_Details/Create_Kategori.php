<?php
session_start();
include "../koneksi.php";
include "../auth.php";
cekAdmin();
include "header_datadetail.php";

if (isset($_POST['insert'])) {
    $Id_kategori = $_POST['Id_kategori'];
    $kategori = $_POST['kategori'];

    if ($Id_kategori != NULL && $kategori != NULL) {
        $sql = "INSERT INTO kategori VALUES ('$Id_kategori','$kategori')";
        $kategori = $koneksi->query($sql);
        if ($kategori) {
            echo "<script>alert('Data kategori berhasil ditambahkan'); window.location.href='Data_Kategori.php';</script>";
        } else {
            echo "<script>alert('Data kategori gagal ditambahkan'); window.location.href='Create_kategori.php';</script>";
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
                    <a href="Data_Kategori.php">Data Kategori</a>
                </li>
                <li class="separator">
                    <i class="icon-arrow-right"></i>
                </li>
                <li class="nav-item">
                    <a href="Create_Kategori.php">Tambah Data Kategori</a>
                </li>
            </ul>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">Tambah Data Kategori</div>
                    </div>
                    <div class="card-body">
                        <form action="Create_Kategori.php" method="post">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="Id_kategori">ID Kategori</label>
                                        <input type="text" name="Id_kategori" class="form-control" id="Id_kategori"
                                            placeholder="Masukan ID Kategori" />
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="kategori">Kategori</label>
                                        <input type="text" name="kategori" class="form-control" id="kategori"
                                            placeholder="Masukan Kategori" />
                                    </div>
                                </div>

                                <!-- Tombol -->
                                <div class="col-12 card-action mt-2">
                                    <button name="insert" class="btn btn-success">Submit</button>
                                    <a href="Data_Kategori.php" class="btn btn-danger">Cancel</a>
                                </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>