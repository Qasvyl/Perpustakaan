<?php
session_start();
include "../koneksi.php";
include "../auth.php";
cekAdmin();
include "header_datadetail.php";


if (isset($_POST['insert'])) {
    $Id_genre = $_POST['Id_genre'];
    $genre = $_POST['genre'];

    if ($Id_genre != NULL && $genre != NULL) {
        $sql = "INSERT INTO genre VALUES ('$Id_genre','$genre')";
        $genre = $koneksi->query($sql);
        if ($genre) {
            echo "<script>alert('Data genre berhasil ditambahkan'); window.location.href='Data_Genre.php';</script>";
        } else {
            echo "<script>alert('Data genre gagal ditambahkan'); window.location.href='Create_Genre.php';</script>";
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
                    <a href="Data_Genre.php">Data Genre</a>
                </li>
                <li class="separator">
                    <i class="icon-arrow-right"></i>
                </li>
                <li class="nav-item">
                    <a href="Create_Genre.php">Tambah Data Genre</a>
                </li>
            </ul>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">Tambah Data Genre</div>
                    </div>
                    <div class="card-body">
                        <form action="Create_Genre.php" method="post">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="Id_genre">ID Genre</label>
                                        <input type="text" name="Id_genre" class="form-control" id="Id_genre"
                                            placeholder="Masukan ID Genre" />
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="genre">Genre</label>
                                        <input type="text" name="genre" class="form-control" id="genre"
                                            placeholder="Masukan Genre" />
                                    </div>
                                </div>

                                <!-- Tombol -->
                                <div class="col-12 card-action mt-2">
                                    <button name="insert" class="btn btn-success">Submit</button>
                                    <a href="Data_Genre.php" class="btn btn-danger">Cancel</a>
                                </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>