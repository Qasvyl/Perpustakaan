<?php
session_start();
include "koneksi.php";
include "auth.php";
cekLogin();
include "header.php";
$bukus = $koneksi->query("SELECT * FROM buku");

// ============================================================
// PROSES EDIT Petugas
// ============================================================
// cek apakah tombol submit ditekan
if (isset($_POST['update'])) {

  // ambil data dari form edit
  $Idp = $_POST['Idp'];
  $qty = $_POST['qty'];
  $Id_buku = $_POST['JudulBuku'];
  $ket = $_POST['ket'];
  // query update
  $query = "UPDATE log_stok SET
                stok_berkurang = '$qty',
                Id_buku = '$Id_buku',
                keterangan = '$ket'
              WHERE id_log = '$Idp'";

  $hasil = mysqli_query($koneksi, $query);

  if ($hasil) {
    echo "<script>
                alert('Data Petugas berhasil di update!');
                window.location.href = 'Log_Stok.php';
              </script>";
  } else {
    echo "<script>
                alert('Data Petugas gagal di update!');
                window.location.href = 'Log_Stok.php';
              </script>";
  }
}
// cek apakah tombol submit ditekan
if (isset($_POST['delete'])) {
  $Idp = $_POST['Idp'];

  $query = "DELETE FROM log_stok WHERE id_log = '$Idp'";

  $hasil = mysqli_query($koneksi, $query);

  if ($hasil) {
    echo "<script>
                alert('Data berhasil di hapus!');
                window.location.href = 'Log_Stok.php';
              </script>";
  } else {
    echo "<script>
                alert('Data gagal di hapus!');
                window.location.href = 'Log_Stok.php';
              </script>";
  }
}
?>

<head>
  <title>Log Stok</title>
</head>

<div class="main-panel">
  <div class="main-header">
    <nav class="navbar navbar-header navbar-header-transparent navbar-expand-lg border-bottom">
      <div class="container-fluid">
        <ul class="navbar-nav topbar-nav ms-md-auto align-items-center">
          <li class="nav-item topbar-user dropdown hidden-caret">
            <a class="dropdown-toggle profile-pic" data-bs-toggle="dropdown" href="#" aria-expanded="false">
              <div class="avatar-sm">
                <img src="image/user.svg" alt="..." class="avatar-img rounded-circle" />
              </div>
              <span class="profile-username">
                <span class="op-7">Hi,</span>
                <span class="fw-bold"><?= htmlspecialchars($_SESSION['Username']) ?></span>
              </span>
            </a>
            <ul class="dropdown-menu dropdown-user animated fadeIn">
              <div class="dropdown-user-scroll scrollbar-outer">
                <li>
                  <div class="user-box">
                    <div class="avatar-lg">
                      <img src="image/user.svg" alt="image profile" class="avatar-img rounded" />
                    </div>
                    <div class="u-text">
                      <h4><?= htmlspecialchars($_SESSION['Username']) ?></h4>
                    </div>
                  </div>
                </li>
                <li>
                  <a class="dropdown-item" href="logout.php">Logout</a>
                </li>
              </div>
            </ul>
          </li>
        </ul>
      </div>
    </nav>
  </div>
  <div class="container">
    <div class="page-inner">
      <div class="page-header">
        <ul class="breadcrumbs mb-3">
          <li class="nav-home">
            <a href="index.php">
              <i class="icon-home"></i>
            </a>
          </li>
          <li class="separator">
            <i class="icon-arrow-right"></i>
          </li>
          <li class="nav-item">
            <a href="Log_Stok.php">Log Stok</a>
          </li>
        </ul>
      </div>
      <div class="row">
        <div class="col-md-12">
          <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
              <h4 class="card-title">Log Stok</h4>
            </div>
            <div class="card-body">
              <div class="table-responsive">
                <table id="basic-datatables" class="display table table-striped table-hover">
                  <thead>
                    <tr>
                      <th>ID Peminjaman</th>
                      <th>Kuantitas Stok</th>
                      <th>Judul</th>
                      <th>Keterangan</th>
                      <?php if ($_SESSION['Role'] == 'Admin'): ?>
                      <th class="justify-align-center text-center">Aksi</th>
                      <?php endif ?>
                    </tr>
                  </thead>
                  <tbody class="table-light">
                    <?php
                    include "koneksi.php";
                    $Log = $koneksi->query("SELECT log_stok.*, log_stok.Id_buku AS id_buku_log, buku.Judul FROM log_stok INNER JOIN buku ON buku.Id_buku = log_stok.Id_buku INNER JOIN peminjaman ON peminjaman.Id_peminjaman = log_stok.Id_peminjaman");
                    $dataLog = $Log->fetch_all(MYSQLI_ASSOC);
                    foreach ($dataLog as $data):
                      ?>
                      <tr>
                        <td><?= htmlspecialchars($data['Id_peminjaman']) ?>
                        </td>
                        <td><?= htmlspecialchars($data['stok_berkurang']) ?>
                        </td>
                        <td>
                          <?= htmlspecialchars($data['Judul']) ?>
                        </td>
                        <td>
                          <?= htmlspecialchars($data['keterangan']) ?>
                        </td>
                        <td class="text-center">
                          <?php if ($_SESSION['Role'] == 'Admin'): ?>
                          <button type="button" class="btn btn-link btn-primary btn-sm" data-bs-toggle="modal"
                            data-bs-target="#edit<?= $data['id_log'] ?>">
                            <i class=" fa fa-edit fa-lg"></i>
                          </button>
                          <button type="button" class="btn btn-link btn-danger btn-sm" data-bs-toggle="modal"
                            data-bs-target="#hapus<?= $data['id_log'] ?>">
                            <i class="fa fa-trash fa-lg"></i>
                          </button>
                          <?php endif; ?>
                        </td>
                      </tr>
                    <?php endforeach; ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <?php foreach ($dataLog as $data): ?>
    <!-- MODAL EDIT -->
    <div class="modal fade" id="edit<?= $data['id_log'] ?>" tabindex="-1" aria-labelledby="exampleModalLabel"
      aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <form action="Log_Stok.php" method="POST">
            <div class="modal-header">
              <h5 class="modal-title">Form Edit Data log</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <input type="hidden" name="Idp" value="<?= $data['id_log'] ?>">
              <div class="mb-3">
                <label class="form-label">Stok Berkurang</label>
                <input type="text" class="form-control" name="qty" value="<?= $data['stok_berkurang'] ?>">
              </div>
              <div class="mb-3">
                <select class="form-select" name="JudulBuku" class="form-control"><?php
                $bukus->data_seek(0);
                foreach ($bukus as $b): ?>
                    <option value="<?= $b['Id_buku'] ?>" <?= $data['id_buku_log'] == $b['Id_buku'] ? 'selected' : '' ?>>
                      <?= htmlspecialchars($b['Judul']) ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>
              <div class="mb-3">
                <label class="form-label">Keterangan</label>
                <input type="text" class="form-control" name="ket" value="<?= $data['keterangan'] ?>">
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
              <button type="submit" class="btn btn-primary" name="update">Simpan</button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <!-- MODAL HAPUS -->
    <div class="modal fade" id="hapus<?= $data['id_log'] ?>" tabindex="-1" aria-labelledby="exampleModalLabel"
      aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <form action="Log_Stok.php" method="post">
            <div class="modal-header">
              <h5 class="modal-title">Konfirmasi Hapus</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
              <input type="hidden" value="<?= $data['id_log'] ?>" name="Idp" />
              <i class=" fas fa-trash text-danger mb-4" style="font-size: 60px;"></i>
              <h5>Apakah anda yakin ingin menghapus data ?</h5>
              <label>ID : <b>
                  <?= $data['Id_peminjaman'] ?>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
              <button type="submit" class="btn btn-danger" name="delete">Hapus</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  <?php endforeach; ?>
  <?php
  include "footer.php";
  ?>