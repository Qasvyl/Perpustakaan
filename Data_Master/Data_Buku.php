<?php
session_start();
include "../koneksi.php";
include "../auth.php";
cekAdmin();
include "header_datatable.php";

$genres = $koneksi->query("SELECT * FROM genre");
$kategoris = $koneksi->query("SELECT * FROM kategori");

// ============================================================
// PROSES EDIT Buku
// ============================================================
if (isset($_POST['update'])) {

  $idb = $_POST['idb'];
  $Judul = $_POST['Judul'];
  $Pengarang = $_POST['Pengarang'];
  $Id_genre = $_POST['Id_genre'];
  $Id_kategori = $_POST['Id_kategori'];
  $foto_lama = $_POST['foto_lama'];
  $Stok = $_POST['Stok'];
  $Harga = $_POST['Harga'];

  // Cek apakah ada file foto baru yang diupload
  if (!empty($_FILES['Foto_buku']['name'])) {
    $nama_file = time() . '_' . basename($_FILES['Foto_buku']['name']);
    $folder_tujuan = '../Gambar/buku/' . $nama_file;
    $tipe_file = strtolower(pathinfo($nama_file, PATHINFO_EXTENSION));
    $tipe_izin = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

    if (!in_array($tipe_file, $tipe_izin)) {
      echo "<script>alert('Format foto tidak didukung!'); window.history.back();</script>";
      exit;
    }

    if (move_uploaded_file($_FILES['Foto_buku']['tmp_name'], $folder_tujuan)) {
      // Hapus foto lama jika ada
      if (!empty($foto_lama) && file_exists('../Gambar/buku/' . $foto_lama)) {
        unlink('../Gambar/buku/' . $foto_lama);
      }
      $Foto_buku = $nama_file;
    } else {
      echo "<script>alert('Gagal mengupload foto!'); window.history.back();</script>";
      exit;
    }
  } else {
    // Tidak ada foto baru, pakai foto lama
    $Foto_buku = $foto_lama;
  }

  $query = "UPDATE buku SET
              Judul       = '$Judul',
              Pengarang   = '$Pengarang',
              Id_genre    = '$Id_genre',
              Id_kategori = '$Id_kategori',
              Foto_buku   = '$Foto_buku',
              Stok   = '$Stok',
              Harga   = '$Harga'
            WHERE Id_buku = '$idb'";

  $hasil = mysqli_query($koneksi, $query);

  if ($hasil) {
    echo "<script>alert('Data Buku berhasil di update!'); window.location.href = 'Data_Buku.php';</script>";
  } else {
    echo "<script>alert('Data Buku gagal di update!'); window.location.href = 'Data_Buku.php';</script>";
  }
}

// ============================================================
// PROSES DELETE Buku
// ============================================================
if (isset($_POST['delete'])) {

  $idb = $_POST['idb'];
  $foto_hapus = $_POST['foto_lama'];

  $query = "DELETE FROM buku WHERE Id_buku = '$idb'";
  $hasil = mysqli_query($koneksi, $query);

  if ($hasil) {
    // Hapus file foto dari server
    if (!empty($foto_hapus) && file_exists('../Gambar/buku/' . $foto_hapus)) {
      unlink('../Gambar/buku/' . $foto_hapus);
    }
    echo "<script>alert('Data Buku berhasil di hapus!'); window.location.href = 'Data_Buku.php';</script>";
  } else {
    echo "<script>alert('Data Buku gagal di hapus!'); window.location.href = 'Data_Buku.php';</script>";
  }
}
?>

<div class="container">
  <div class="page-inner">
    <div class="page-header">
      <ul class="breadcrumbs mb-3">
        <li class="nav-home">
          <a href="../index.php"><i class="icon-home"></i></a>
        </li>
        <li class="separator"><i class="icon-arrow-right"></i></li>
        <li class="nav-item"><a href="">Data Master</a></li>
        <li class="separator"><i class="icon-arrow-right"></i></li>
        <li class="nav-item"><a href="Data_Buku.php">Data Buku</a></li>
      </ul>
    </div>

    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="card-title">Data Buku</h4>
            <a class="btn btn-primary" href="Create_Buku.php">
              <i class="fas fa-plus me-2"></i>Tambah Buku
            </a>
          </div>

          <div class="card-body">
            <div class="table-responsive">
              <table id="basic-datatables" class="display table table-striped table-hover">
                <thead>
                  <tr>
                    <th>No</th>
                    <th>Cover Buku</th>
                    <th>Judul</th>
                    <th>Pengarang</th>
                    <th>Genre</th>
                    <th>Kategori</th>
                    <th>Stok</th>
                    <th>Harga</th>
                    <th class="text-center">Aksi</th>
                  </tr>
                </thead>
                <tbody class="table-light">
                  <?php
                  $Buku = $koneksi->query("SELECT * FROM buku INNER JOIN genre ON buku.Id_genre = genre.Id_genre INNER JOIN kategori ON buku.Id_kategori = kategori.Id_kategori;");
                  $no = 1;
                  foreach ($Buku as $data):
                    ?>
                    <tr>
                      <td><?= $no++ ?></td>

                      <td>
                        <?php if (!empty($data['Foto_buku'])): ?>
                          <img src="../Gambar/buku/<?= htmlspecialchars($data['Foto_buku']) ?>" alt="Foto Buku"
                            style="width: 80px; height: 130px; object-fit: cover; border-radius: 4px;">
                        <?php else: ?>
                          <span class="text-muted"><i class="fas fa-image fa-2x"></i></span>
                        <?php endif; ?>
                      </td>

                      <td><?= htmlspecialchars($data['Judul']) ?></td>
                      <td><?= htmlspecialchars($data['Pengarang']) ?></td>
                      <td><?= htmlspecialchars($data['genre']) ?></td>
                      <td><?= htmlspecialchars($data['kategori']) ?></td>
                      <td><?= htmlspecialchars($data['Stok']) ?></td>
                      <td>Rp <?= number_format($data['Harga'], 0, ',', '.') ?></td>
                      <td class="text-center">
                        <button type="button" class="btn btn-link btn-primary btn-sm" data-bs-toggle="modal"
                          data-bs-target="#edit<?= $data['Id_buku'] ?>">
                          <i class="fa fa-edit fa-lg"></i>
                        </button>
                        <button type="button" class="btn btn-link btn-danger btn-sm" data-bs-toggle="modal"
                          data-bs-target="#hapus<?= $data['Id_buku'] ?>">
                          <i class="fa fa-trash fa-lg"></i>
                        </button>
                      </td>
                    </tr>

                    <!-- ============================================================
                       MODAL EDIT Buku
                  ============================================================ -->
                    <div class="modal fade" id="edit<?= $data['Id_buku'] ?>" tabindex="-1"
                      aria-labelledby="exampleModalLabel" aria-hidden="true">
                      <div class="modal-dialog">
                        <div class="modal-content">
                          <!-- enctype wajib untuk upload file -->
                          <form action="Data_Buku.php" method="POST" enctype="multipart/form-data">
                            <div class="modal-header">
                              <h5 class="modal-title">Form Edit Data Buku</h5>
                              <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>

                            <div class="modal-body">
                              <!-- Hidden fields -->
                              <input type="hidden" name="idb" value="<?= $data['Id_buku'] ?>">
                              <input type="hidden" name="foto_lama" value="<?= htmlspecialchars($data['Foto_buku']) ?>">

                              <div class="mb-3">
                                <label class="form-label">Judul Buku</label>
                                <input type="text" class="form-control" name="Judul"
                                  value="<?= htmlspecialchars($data['Judul']) ?>">
                              </div>

                              <div class="mb-3">
                                <label class="form-label">Pengarang</label>
                                <input type="text" class="form-control" name="Pengarang"
                                  value="<?= htmlspecialchars($data['Pengarang']) ?>">
                              </div>

                              <div class="mb-3">
                                <label for="Genre" class="form-label">Genre</label>
                                <select class="form-select" name="Id_genre" id="Genre" class="form-control"><?php
                                $genres->data_seek(0); 
                                foreach ($genres as $g): ?>
                                    <option value="<?= $g['Id_genre'] ?>" <?= $data['Id_genre'] == $g['Id_genre'] ? 'selected' : '' ?>>
                                      <?= htmlspecialchars($g['genre']) ?>
                                    </option>
                                  <?php endforeach; ?>
                                </select>
                              </div>

                              <div class="mb-3">
                                <label for="Kategori" class="form-label">Kategori</label>
                                <select class="form-select" name="Id_kategori" id="Kategori" class="form-control">
                                  <?php
                                  $kategoris->data_seek(0);
                                  foreach ($kategoris as $k): ?>
                                    <option value="<?= $k['Id_kategori'] ?>" <?= $data['Id_kategori'] == $k['Id_kategori'] ? 'selected' : '' ?>>
                                      <?= htmlspecialchars($k['kategori']) ?>
                                    </option>
                                  <?php endforeach; ?>
                                </select>
                              </div>

                              <div class="mb-3">
                                <label class="form-label">Stok</label>
                                <input type="text" class="form-control" name="Stok"
                                  value="<?= htmlspecialchars($data['Stok']) ?>">
                              </div>
                              <div class="mb-3">
                                <label class="form-label">Harga</label>
                                <input type="text" class="form-control" name="Harga"
                                  value="<?= htmlspecialchars($data['Harga']) ?>">
                              </div>

                              <!-- Upload Foto -->
                              <div class="mb-3">
                                <label class="form-label">Foto Buku</label>

                                <!-- Preview foto saat ini -->
                                <?php if (!empty($data['Foto_buku'])): ?>
                                  <div class="mb-2">
                                    <img src="../Gambar/buku/<?= htmlspecialchars($data['Foto_buku']) ?>"
                                      alt="Foto saat ini"
                                      style="width: 80px; height: 100px; object-fit: cover; border-radius: 4px; border: 1px solid #ddd;">
                                    <small class="d-block text-muted mt-1">Foto saat ini</small>
                                  </div>
                                <?php endif; ?>

                                <input type="file" class="form-control" name="Foto_buku"
                                  accept="image/jpg, image/jpeg, image/png, image/gif, image/webp">
                                <small class="text-muted">Kosongkan jika tidak ingin mengubah foto. Format: JPG, PNG, GIF,
                                  WEBP</small>
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

                    <!-- ============================================================
                       MODAL HAPUS Buku
                  ============================================================ -->
                    <div class="modal fade" id="hapus<?= $data['Id_buku'] ?>" tabindex="-1"
                      aria-labelledby="exampleModalLabel" aria-hidden="true">
                      <div class="modal-dialog">
                        <div class="modal-content">
                          <form action="Data_Buku.php" method="POST">
                            <div class="modal-header">
                              <h5 class="modal-title">Konfirmasi Hapus</h5>
                              <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>

                            <div class="modal-body text-center">
                              <input type="hidden" name="idb" value="<?= $data['Id_buku'] ?>">
                              <input type="hidden" name="foto_lama" value="<?= htmlspecialchars($data['Foto_buku']) ?>">

                              <i class="fas fa-trash text-danger mb-4" style="font-size: 60px;"></i>
                              <h5>Apakah anda yakin ingin menghapus data ?</h5>

                              <?php if (!empty($data['Foto_buku'])): ?>
                                <img src="../Gambar/buku/<?= htmlspecialchars($data['Foto_buku']) ?>" alt="Foto Buku"
                                  style="width: 60px; height: 80px; object-fit: cover; border-radius: 4px; margin-bottom: 8px;">
                              <?php endif; ?>

                              <br>
                              <label>Judul Buku : <b><?= htmlspecialchars($data['Judul']) ?></b></label>
                            </div>

                            <div class="modal-footer">
                              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                              <button type="submit" class="btn btn-danger" name="delete">Hapus</button>
                            </div>
                          </form>
                        </div>
                      </div>
                    </div>

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

<?php include "footer_datatable.php"; ?>