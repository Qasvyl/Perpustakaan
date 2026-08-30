<?php
session_start();
include "../koneksi.php";
include "../auth.php";
cekAdmin();
include "header_datatable.php";

// ============================================================
// PROSES EDIT Petugas
// ============================================================
// cek apakah tombol submit ditekan
if (isset($_POST['update'])) {

  // ambil data dari form edit
  $idpet = $_POST['idpet'];
  $Username = $_POST['Username'];
  $JenisKelamin = $_POST['JenisKelamin'];
  $Role = $_POST['Role'];
  // query update
  $query = "UPDATE petugas SET
                Username = '$Username',
                JenisKelamin = '$JenisKelamin',
                Role = '$Role'
              WHERE Id_petugas = '$idpet'";

  $hasil = mysqli_query($koneksi, $query);

  if ($hasil) {
    echo "<script>
                alert('Data Petugas berhasil di update!');
                window.location.href = 'Data_Petugas.php';
              </script>";
  } else {
    echo "<script>
                alert('Data Petugas gagal di update!');
                window.location.href = 'Data_Petugas.php';
              </script>";
  }
}
// cek apakah tombol submit ditekan
if (isset($_POST['delete'])) {

  // ambil data dari form hapus
  $idpet = $_POST['idpet'];

  // query update
  $query = "DELETE FROM petugas WHERE Id_petugas = '$idpet'";

  $hasil = mysqli_query($koneksi, $query);

  if ($hasil) {
    echo "<script>
                alert('Data Petugas berhasil di hapus!');
                window.location.href = 'Data_Petugas.php';
              </script>";
  } else {
    echo "<script>
                alert('Data Petugas gagal di hapus!');
                window.location.href = 'Data_Petugas.php';
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
          <a href="">Data Master</a>
        </li>
        <li class="separator">
          <i class="icon-arrow-right"></i>
        </li>
        <li class="nav-item">
          <a href="Data_Petugas.php">Data Petugas</a>
        </li>
      </ul>
    </div>
    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="card-title">Data Petugas</h4>
            <a class="btn btn-primary" href="Create_Petugas.php"><i class="fas fa-plus me-2"></i>Tambah Petugas</a>
          </div>
          <div class="card-body">
            <div class="table-responsive">
              <table id="basic-datatables" class="display table table-striped table-hover">
                <thead>
                  <tr>
                    <th>No</th>
                    <th>Username</th>
                    <th>Gender</th>
                    <th>Role</th>
                    <th class="justify-align-center text-center">Aksi</th>
                  </tr>
                </thead>
                <tbody class="table-light">
                  <?php
                  include "../koneksi.php";
                  $Petugas = $koneksi->query("SELECT * FROM petugas");
                  $no = 1;
                  foreach ($Petugas as $data):
                    ?>
                    <tr>
                      <td><?= $no++ ?></td>
                      <td><?= htmlspecialchars($data['Username']) ?></td>
                      <td><?= htmlspecialchars($data['JenisKelamin']) ?></td>
                      <td><?= htmlspecialchars($data['Role']) ?></td>
                      <td class="text-center">
                        <button type="button" class="btn btn-link btn-primary btn-sm" data-bs-toggle="modal"
                          data-bs-target="#edit<?= $data['Id_petugas'] ?>">
                          <i class="fa fa-edit fa-lg"></i>
                        </button>
                        <button type="button" class="btn btn-link btn-danger btn-sm" data-bs-toggle="modal"
                          data-bs-target="#hapus<?= $data['Id_petugas'] ?>">
                          <i class="fa fa-trash fa-lg"></i>
                        </button>
                      </td>
                    </tr>

                    <!-- MODAL EDIT -->
                    <div class="modal fade" id="edit<?= $data['Id_petugas'] ?>" tabindex="-1"
                      aria-labelledby="exampleModalLabel" aria-hidden="true">
                      <div class="modal-dialog">
                        <div class="modal-content">
                          <form action="Data_Petugas.php" method="POST">
                            <div class="modal-header">
                              <h5 class="modal-title" id="exampleModalLabel">Form Edit Data petugas</h5>
                              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>

                            <div class="modal-body">
                              <input type="hidden" name="idpet" id="idpet" value="<?= $data['Id_petugas'] ?>">

                              <div class="mb-3">
                                <label for="Username" class="form-label">Username</label>
                                <input type="text" class="form-control" name="Username" id="Username"
                                  value="<?= $data['Username'] ?>">
                              </div>

                              <div class="mb-3">
                                <label for="JenisKelamin" class="form-label">Jenis Kelamin</label>
                                <select class="form-select" name="JenisKelamin" id="JenisKelamin" class="form-control">
                                  <option value="Pria" <?= $data['JenisKelamin'] == 'Pria' ? 'selected' : '' ?>>Pria</option>
                                  <option value="Wanita" <?= $data['JenisKelamin'] == 'Wanita' ? 'selected' : '' ?>>Wanita
                                  </option>
                                </select>
                              </div>

                              <div class="mb-3">
                                <label for="Role" class="form-label">Role</label>
                                <select class="form-select" name="Role" id="Role" class="form-control">
                                  <option value="Admin" <?= $data['Role'] == 'Admin' ? 'selected' : '' ?>>Admin</option>
                                  <option value="Petugas" <?= $data['Role'] == 'Petugas' ? 'selected' : '' ?>>Petugas
                                  </option>
                                </select>
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
                    <div class="modal fade" id="hapus<?= $data['Id_petugas'] ?>" tabindex="-1"
                      aria-labelledby="exampleModalLabel" aria-hidden="true">
                      <div class="modal-dialog">
                        <div class="modal-content">
                          <form action="Data_Petugas.php" method="post">
                            <div class="modal-header">
                              <h5 class="modal-title" id="exampleModalLabel">Konfirmasi Hapus</h5>
                              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>

                            <div class="modal-body text-center justify-align-center">
                              <input type="hidden" value="<?= $data['Id_petugas'] ?>" name="idpet" />


                              <i class="fas fa-trash text-danger mb-4" style="font-size: 60px;"></i>
                              <h5>Apakah anda yakin ingin menghapus data ?</h5>
                              <label>Username Petugas : <b><?= $data['Username'] ?></b></label>
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
include "footer_datatable.php";
?>