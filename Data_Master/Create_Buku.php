<?php
session_start();
include "../koneksi.php";
include "../auth.php";
cekAdmin();
include "header_datatable.php";

$genres = $koneksi->query("SELECT * FROM genre");
$kategoris = $koneksi->query("SELECT * FROM kategori");

if (isset($_POST['insert'])) {
    $Judul = $_POST['Judul'];
    $Pengarang = $_POST['Pengarang'];
    $Id_genre = $_POST['Id_genre'];
    $Id_kategori = $_POST['Id_kategori'];
    $Stok = $_POST['Stok'];
    $Harga = $_POST['Harga'];

    $foto = 'default.jpg';

    if (isset($_FILES['Foto_buku']) && $_FILES['Foto_buku']['error'] == 0) {
        $ekstensi_allowed = ['jpg', 'jpeg', 'png', 'webp'];
        $ekstensi = strtolower(pathinfo($_FILES['Foto_buku']['name'], PATHINFO_EXTENSION));

        if (in_array($ekstensi, $ekstensi_allowed)) {

            $folder = '../Gambar/buku/';


            $nama_file = time() . '_' . basename($_FILES['Foto_buku']['name']);
            $tujuan = $folder . $nama_file;

            if (move_uploaded_file($_FILES['Foto_buku']['tmp_name'], $tujuan)) {
                $foto = $nama_file; // update $foto ke nama file
            } else {
                echo "<script>alert('Foto gagal diupload, data disimpan tanpa foto.');</script>";
            }
        } else {
            echo "<script>alert('Format foto tidak didukung! Gunakan JPG, PNG, atau WEBP.');</script>";
        }
    }

    if ($Judul != NULL && $Pengarang != NULL && $Id_genre != NULL && $Id_kategori != NULL && $Stok != NULL && $Harga != NULL) {
        $sql = "INSERT INTO buku VALUES (null,'$Judul','$Pengarang','$Id_genre','$Id_kategori','$foto','$Stok','$Harga')";
        $buku = $koneksi->query($sql);

        if ($buku) {
            echo "<script>alert('Data Buku berhasil ditambahkan'); window.location.href='Data_Buku.php';</script>";
        } else {
            echo "<script>alert('Data Buku gagal ditambahkan'); window.location.href='Create_Buku.php';</script>";
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
                    <a href="Data_Buku.php">Data Buku</a>
                </li>
                <li class="separator">
                    <i class="icon-arrow-right"></i>
                </li>
                <li class="nav-item">
                    <a href="Create_Buku.php">Tambah Data Buku</a>
                </li>
            </ul>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">Tambah Data Buku</div>
                    </div>
                    <div class="card-body">
                        <form action="Create_Buku.php" method="post" enctype="multipart/form-data">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="judul">Judul</label>
                                        <input type="text" name="Judul" class="form-control" id="judul"
                                            placeholder="Masukan Judul Buku" />
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="pengarang">Pengarang</label>
                                        <input type="text" name="Pengarang" class="form-control" id="pengarang"
                                            placeholder="Masukan Nama Pengarang" />
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="Id_genre">Genre</label>
                                        <select class="form-select" name="Id_genre">
                                            <?php foreach ($genres as $g): ?>
                                                <option value="<?= $g['Id_genre'] ?>">
                                                    <?= htmlspecialchars($g['genre']) ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="Id_kategori">Kategori</label>
                                        <select class="form-select" name="Id_kategori">
                                            <?php foreach ($kategoris as $k): ?>
                                                <option value="<?= $k['Id_kategori'] ?>">
                                                    <?= htmlspecialchars($k['kategori']) ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="Stok">Stok</label>
                                        <input type="text" name="Stok" class="form-control" id="Stok"
                                            placeholder="Masukan Stok" />
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="Harga">Harga</label>
                                        <input type="text" name="Harga" class="form-control" id="harga"
                                            placeholder="Masukan Harga" />
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="Foto_buku">Foto</label>
                                        <input type="file" name="Foto_buku" class="form-control" id="foto"
                                            placeholder="Masukan Foto" />
                                    </div>
                                </div>
                            </div>

                            <!-- Tombol -->
                            <div class="col-12 card-action mt-2">
                                <button name="insert" class="btn btn-success">Submit</button>
                                <a href=Data_Buku.php class="btn btn-danger">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>