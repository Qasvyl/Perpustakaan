<?php
session_start();
include "../koneksi.php";
include "../auth.php";
cekAdmin();
include "header_datadetail.php";

// ============================================================
// PROSES EDIT Kategori
// ============================================================
// cek apakah tombol submit ditekan
if (isset($_POST['update'])) {

  // ambil data dari form edit
  $idk = $_POST['idk'];
  $kategori = $_POST['Kategori'];
  // query update
  $query = "UPDATE kategori SET
                Id_kategori = '$idk',
                kategori = '$kategori'
              WHERE Id_kategori = '$idk'";

  $hasil = mysqli_query($koneksi, $query);

  if ($hasil) {
    echo "<script>
                alert('Data Kategori berhasil di update!');
                window.location.href = 'Data_Kategori.php';
              </script>";
  } else {
    echo "<script>
                alert('Data Kategori gagal di update!');
                window.location.href = 'Data_Kategori.php';
              </script>";
  }
}
// cek apakah tombol submit ditekan
if (isset($_POST['delete'])) {

  // ambil data dari form hapus
  $idk = $_POST['idk'];

  // query update
  $query = "DELETE FROM kategori WHERE Id_kategori = '$idk'";

  $hasil = mysqli_query($koneksi, $query);

  if ($hasil) {
    echo "<script>
                alert('Data Kategori berhasil di hapus!');
                window.location.href = 'Data_Kategori.php';
              </script>";
  } else {
    echo "<script>
                alert('Data Kategori gagal di hapus!');
                window.location.href = 'Data_Kategori.php';
              </script>";
  }
}
?>
<div class="container">
  <div class="page-inner">
    <div class="page-header">
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
          <a href="">Data Detail</a>
        </li>
        <li class="separator">
          <i class="icon-arrow-right"></i>
        </li>
        <li class="nav-item">
          <a href="Data_Kategori.php">Data Kategori</a>
        </li>
      </ul>
    </div>
    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="card-title">Data Kategori</h4>
            <a class="btn btn-primary" href="Create_Kategori.php"><i class="fas fa-plus me-2"></i>Tambah Kategori</a>
          </div>
          <div class="card-body">
            <div class="table-responsive">
              <table id="basic-datatables" class="display table table-striped table-hover">
                <thead>
                  <tr>
                    <th>ID Kategori</th>
                    <th>Kategori</th>
                    <th class="justify-align-center text-center">Aksi</th>
                  </tr>
                </thead>
                <tbody class="table-light">
                  <?php
                  include "../koneksi.php";
                  $Kategori = $koneksi->query("SELECT * FROM kategori");
                  foreach ($Kategori as $data):
                    ?>
                    <tr>
                      <td><?= htmlspecialchars($data['Id_kategori']) ?></td>
                      <td><?= htmlspecialchars($data['kategori']) ?></td>
                      <td class="text-center">
                        <button type="button" class="btn btn-link btn-primary btn-sm" data-bs-toggle="modal"
                          data-bs-target="#edit<?= $data['Id_kategori'] ?>">
                          <i class="fa fa-edit fa-lg"></i>
                        </button>
                        <button type="button" class="btn btn-link btn-danger btn-sm" data-bs-toggle="modal"
                          data-bs-target="#hapus<?= $data['Id_kategori'] ?>">
                          <i class="fa fa-trash fa-lg"></i>
                        </button>
                      </td>
                    </tr>

                    <!-- MODAL EDIT -->
                    <div class="modal fade" id="edit<?= $data['Id_kategori'] ?>" tabindex="-1"
                      aria-labelledby="exampleModalLabel" aria-hidden="true">
                      <div class="modal-dialog">
                        <div class="modal-content">
                          <form action="Data_Kategori.php" method="POST">
                            <div class="modal-header">
                              <h5 class="modal-title" id="exampleModalLabel">Form Edit Data Kategori</h5>
                              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>

                            <div class="modal-body">
                              <input type="hidden" name="idk" id="idk" value="<?= $data['Id_kategori'] ?>">

                              <div class="mb-3">
                                <label for="Id_Kategori" class="form-label">ID Kategori</label>
                                <input type="text" class="form-control" name="Id_Kategori" id="Id_Kategori"
                                  value="<?= $data['Id_kategori'] ?>">
                              </div>

                              <div class="mb-3">
                                <label for="Kategori" class="form-label">Kategori</label>
                                <input type="text" class="form-control" name="Kategori" id="Kategori"
                                  value="<?= $data['kategori'] ?>">
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
                    <div class="modal fade" id="hapus<?= $data['Id_kategori'] ?>" tabindex="-1"
                      aria-labelledby="exampleModalLabel" aria-hidden="true">
                      <div class="modal-dialog">
                        <div class="modal-content">
                          <form action="Data_Kategori.php" method="POST">
                            <div class="modal-header">
                              <h5 class="modal-title" id="exampleModalLabel">Konfirmasi Hapus</h5>
                              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>

                            <div class="modal-body text-center justify-align-center">
                              <input type="hidden" value="<?= $data['Id_kategori'] ?>" name="idk" />


                              <i class="fas fa-trash text-danger mb-4" style="font-size: 60px;"></i>
                              <h5>Apakah anda yakin ingin menghapus data ?</h5>
                              <label>Kategori : <b><?= $data['kategori'] ?></b></label>
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

<?php
include "footer_datadetail.php";
?>