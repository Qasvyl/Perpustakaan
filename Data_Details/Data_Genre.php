<?php
session_start();
include "../koneksi.php";
include "../auth.php";
cekAdmin();
include "header_datadetail.php";

// ============================================================
// PROSES EDIT Genre
// ============================================================
// cek apakah tombol submit ditekan
if (isset($_POST['update'])) {

  // ambil data dari form edit
  $idg = $_POST['idg'];
  $genre = $_POST['Genre'];
  // query update
  $query = "UPDATE genre SET
                Id_genre = '$idg',
                genre = '$genre'
              WHERE Id_genre = '$idg'";

  $hasil = mysqli_query($koneksi, $query);

  if ($hasil) {
    echo "<script>
                alert('Data Genre berhasil di update!');
                window.location.href = 'Data_Genre.php';
              </script>";
  } else {
    echo "<script>
                alert('Data Genre gagal di update!');
                window.location.href = 'Data_Genre.php';
              </script>";
  }
}
// cek apakah tombol submit ditekan
if (isset($_POST['delete'])) {

  // ambil data dari form hapus
  $idg = $_POST['idg'];

  // query update
  $query = "DELETE FROM genre WHERE Id_genre = '$idg'";

  $hasil = mysqli_query($koneksi, $query);

  if ($hasil) {
    echo "<script>
                alert('Data Genre berhasil di hapus!');
                window.location.href = 'Data_Genre.php';
              </script>";
  } else {
    echo "<script>
                alert('Data Genre gagal di hapus!');
                window.location.href = 'Data_Genre.php';
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
          <a href="Data_Genre.php">Data Genre</a>
        </li>
      </ul>
    </div>
    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="card-title">Data Genre</h4>
            <a class="btn btn-primary" href="Create_Genre.php"><i class="fas fa-plus me-2"></i>Tambah Genre</a>
          </div>
          <div class="card-body">
            <div class="table-responsive">
              <table id="basic-datatables" class="display table table-striped table-hover">
                <thead>
                  <tr>
                    <th>ID Genre</th>
                    <th>Genre</th>
                    <th class="justify-align-center text-center">Aksi</th>
                  </tr>
                </thead>
                <tbody class="table-light">
                  <?php
                  include "../koneksi.php";
                  $Genre = $koneksi->query("SELECT * FROM genre");
                  foreach ($Genre as $data):
                    ?>
                    <tr>
                      <td><?= htmlspecialchars($data['Id_genre']) ?></td>
                      <td><?= htmlspecialchars($data['genre']) ?></td>
                      <td class="text-center">
                        <button type="button" class="btn btn-link btn-primary btn-sm" data-bs-toggle="modal"
                          data-bs-target="#edit<?= $data['Id_genre'] ?>">
                          <i class="fa fa-edit fa-lg"></i>
                        </button>
                        <button type="button" class="btn btn-link btn-danger btn-sm" data-bs-toggle="modal"
                          data-bs-target="#hapus<?= $data['Id_genre'] ?>">
                          <i class="fa fa-trash fa-lg"></i>
                        </button>
                      </td>
                    </tr>

                    <!-- MODAL EDIT -->
                    <div class="modal fade" id="edit<?= $data['Id_genre'] ?>" tabindex="-1"
                      aria-labelledby="exampleModalLabel" aria-hidden="true">
                      <div class="modal-dialog">
                        <div class="modal-content">
                          <form action="Data_Genre.php" method="POST">
                            <div class="modal-header">
                              <h5 class="modal-title" id="exampleModalLabel">Form Edit Data Genre</h5>
                              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>

                            <div class="modal-body">
                              <input type="hidden" name="idg" id="idg" value="<?= $data['Id_genre'] ?>">

                              <div class="mb-3">
                                <label for="Id_Genre" class="form-label">ID Genre</label>
                                <input type="text" class="form-control" name="Id_Genre" id="Id_Genre"
                                  value="<?= $data['Id_genre'] ?>">
                              </div>

                              <div class="mb-3">
                                <label for="Genre" class="form-label">Genre</label>
                                <input type="text" class="form-control" name="Genre" id="Genre"
                                  value="<?= $data['genre'] ?>">
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
                    <div class="modal fade" id="hapus<?= $data['Id_genre'] ?>" tabindex="-1"
                      aria-labelledby="exampleModalLabel" aria-hidden="true">
                      <div class="modal-dialog">
                        <div class="modal-content">
                          <form action="Data_Genre.php" method="POST">
                            <div class="modal-header">
                              <h5 class="modal-title" id="exampleModalLabel">Konfirmasi Hapus</h5>
                              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>

                            <div class="modal-body text-center justify-align-center">
                              <input type="hidden" value="<?= $data['Id_genre'] ?>" name="idg" />


                              <i class="fas fa-trash text-danger mb-4" style="font-size: 60px;"></i>
                              <h5>Apakah anda yakin ingin menghapus data ?</h5>
                              <label>Genre : <b><?= $data['genre'] ?></b></label>
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