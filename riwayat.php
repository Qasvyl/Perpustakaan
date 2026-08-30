<?php
session_start();
include "koneksi.php";
include "auth.php";
cekLogin();
include "header.php";
?>
<head><title>Riwayat</title></head>

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
          <a href="riwayat.php">Riwayat</a>
        </li>
      </ul>
    </div>
    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="card-title">Riwayat Transaksi</h4>
          </div>
          <div class="card-body">
            <div class="table-responsive">
              <table id="basic-datatables" class="display table table-striped table-hover">
                <thead>
                  <tr>
                    <th>ID Peminjaman</th>
                    <th>Tanggal Pinjam</th>
                    <th>Tanggal Kembali</th>
                    <th>Judul</th>
                    <th>Petugas Pinjam</th>
                    <th>Petugas Kembali</th>
                  </tr>
                </thead>
                <tbody class="table-light">
                  <?php
                  include "koneksi.php";
                  $Riwayat = $koneksi->query("SELECT * FROM riwayat INNER JOIN buku ON buku.Id_buku = riwayat.Id_buku INNER JOIN peminjaman ON peminjaman.Id_peminjaman = riwayat.Id_peminjaman");
                  foreach ($Riwayat as $data):
                    ?>
                    <tr>
                      <td><?= htmlspecialchars($data['Id_peminjaman']) ?></td>
                      <td><?= htmlspecialchars($data['Tgl_Peminjaman']) ?></td>
                      <td><?= htmlspecialchars($data['Tgl_Pengembalian']) ?></td>
                      <td><?= htmlspecialchars($data['Judul']) ?></td>
                      <td><?= htmlspecialchars($data['Idp_pinjam']) ?></td>
                      <td><?= htmlspecialchars($data['Idp_kembali']) ?></td>
                    </tr>
                  <?php endforeach ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>
</div>

<?php
include "footer.php";
?>