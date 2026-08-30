<?php
session_start();
include "../koneksi.php";
include "../auth.php";
cekLogin();
include "header_transaksi.php";

include "kode_pinjam.php";
$kodepinjam = generateKodePinjam($koneksi);
$kodekembali = generateKodeKembali($koneksi);
$petugas = $_SESSION['Username'];
$bukus = $koneksi->query("SELECT * FROM buku");

// TAMBAH PEMINJAMAN
if (isset($_POST['pinjam'])) {
    $Id_Peminjaman = $_POST['Id_Peminjaman'];
    $NIK           = $_POST['NIK'];
    $Nama_peminjam = $_POST['Nama_peminjam'];
    $Alamat        = $_POST['Alamat'];
    $No_Telp       = $_POST['No_Telp'];
    $tgl_pinjam    = $_POST['tgl_pinjam'];
    $tgl_kembali   = $_POST['tgl_kembali'];
    $id_buku       = $_POST['id_buku'];
    $jumlah        = $_POST['jumlah'];

    $query = "INSERT INTO peminjaman 
                (Id_peminjaman, NIK, Nama_peminjam, Alamat, No_Telp, Tgl_pinjam, Tgl_kembali, Id_buku, Jumlah, Idp_pinjam)
              VALUES 
                ('$Id_Peminjaman','$NIK','$Nama_peminjam','$Alamat','$No_Telp','$tgl_pinjam','$tgl_kembali','$id_buku','$jumlah','$petugas')";

    $hasil = mysqli_query($koneksi, $query);
    if ($hasil) {
        mysqli_query($koneksi, "UPDATE buku SET Stok = Stok - $jumlah WHERE Id_buku = '$id_buku'");
        echo "<script>alert('Data Peminjaman berhasil ditambahkan!'); window.location.href='Peminjaman.php';</script>";
    } else {
        echo "<script>alert('Data Peminjaman gagal ditambahkan!'); window.location.href='Peminjaman.php';</script>";
    }
}

// UPDATE PEMINJAMAN
if (isset($_POST['update'])) {
    $Idp = $_POST['Idp'];
    $Nama_peminjam = $_POST['Nama_peminjam'];
    $Alamat = $_POST['Alamat'];
    $No_Telp = $_POST['No_Telp'];
    $Tgl_pinjam = $_POST['Tgl_pinjam'];
    $Tgl_kembali = $_POST['Tgl_kembali'];
    $Id_buku_baru = $_POST['Id_buku'];

    // Ambil data peminjaman lama untuk cek buku & jumlah
    $cek = mysqli_fetch_assoc(mysqli_query(
        $koneksi,
        "SELECT Id_buku, Jumlah FROM peminjaman WHERE Id_peminjaman = '$Idp'"
    ));
    $Id_buku_lama = $cek['Id_buku'];
    $jumlah = $cek['Jumlah'];

    // Jika buku diganti, update stok
    if ($Id_buku_lama != $Id_buku_baru) {
        // Kembalikan stok buku lama
        mysqli_query(
            $koneksi,
            "UPDATE buku SET Stok = Stok + $jumlah WHERE Id_buku = '$Id_buku_lama'"
        );
        // Kurangi stok buku baru
        mysqli_query(
            $koneksi,
            "UPDATE buku SET Stok = Stok - $jumlah WHERE Id_buku = '$Id_buku_baru'"
        );
    }

    $query = "UPDATE peminjaman SET
                Nama_peminjam = '$Nama_peminjam',
                Alamat        = '$Alamat',
                No_Telp       = '$No_Telp',
                Tgl_pinjam    = '$Tgl_pinjam',
                Tgl_kembali   = '$Tgl_kembali',
                Id_buku       = '$Id_buku_baru'
              WHERE Id_peminjaman = '$Idp'";

    $hasil = mysqli_query($koneksi, $query);
    if ($hasil) {
        echo "<script>alert('Data Peminjam berhasil di update!'); window.location.href='Peminjaman.php';</script>";
    } else {
        // Jika update gagal, rollback stok
        if ($Id_buku_lama != $Id_buku_baru) {
            mysqli_query(
                $koneksi,
                "UPDATE buku SET Stok = Stok - $jumlah WHERE Id_buku = '$Id_buku_lama'"
            );
            mysqli_query(
                $koneksi,
                "UPDATE buku SET Stok = Stok + $jumlah WHERE Id_buku = '$Id_buku_baru'"
            );
        }
        echo "<script>alert('Data Peminjam gagal di update!'); window.location.href='Peminjaman.php';</script>";
    }
}

// HAPUS PEMINJAMAN
if (isset($_POST['delete'])) {
    $idp = $_POST['idp'];
    $query = "DELETE FROM peminjaman WHERE Id_peminjaman = '$idp'";
    $hasil = mysqli_query($koneksi, $query);
    if ($hasil) {
        echo "<script>alert('Data Peminjam berhasil di hapus!'); window.location.href='Peminjaman.php';</script>";
    } else {
        echo "<script>alert('Data Peminjam gagal di hapus!'); window.location.href='Peminjaman.php';</script>";
    }
}

// ============================================================
// PENGEMBALIAN BUKU
if (isset($_POST['kembalikan'])) {
    $idp = $_POST['idp_kembali'];
    $id_buku = $_POST['id_buku_kembali'];
    $jumlah = $_POST['jumlah_kembali'];
    $tgl_kembali_plan = $_POST['tgl_kembali_plan'];
    $Tgl_pinjam = $_POST['Tgl_pinjam'];
    $harga_buku = (float) $_POST['harga_buku_kembali'];
    $kondisi_buku = $_POST['kondisi_buku'];
    $idk = $kodepinjam;
    $tgl_aktual = date('Y-m-d');

    // Hitung hari terlambat
    $tgl_plan_obj = new DateTime($tgl_kembali_plan);
    $tgl_aktual_obj = new DateTime($tgl_aktual);
    $hari_terlambat = ($tgl_aktual_obj > $tgl_plan_obj)
        ? (int) $tgl_aktual_obj->diff($tgl_plan_obj)->days
        : 0;

    // Hitung denda
    $batas_denda_terlambat = $harga_buku / 2;
    $denda_terlambat = min($hari_terlambat * 5000, $batas_denda_terlambat);
    $denda_rusak = ($kondisi_buku === 'rusak') ? $harga_buku : 0;
    $denda_total = $denda_terlambat + $denda_rusak;

    // Tentukan status
    if ($kondisi_buku === 'rusak') {
        $status = 'Dikembalikan';
    } elseif ($hari_terlambat > 0) {
        $status = 'Dikembalikan';
    } else {
        $status = 'Dikembalikan';
    }

    $query = "UPDATE peminjaman SET
                Status          = '$status',
                Tgl_kembali     = '$tgl_aktual',
                Denda           = '$denda_total',
                Id_pengembalian = '$kodekembali',
                Kondisi_kembali = '$kondisi_buku',
                Idp_kembali = '$petugas'
              WHERE Id_peminjaman = '$idp'";

    $hasil = mysqli_query($koneksi, $query);
    if ($hasil) {
        $r_query = "INSERT INTO riwayat (Tgl_Peminjaman, Tgl_Pengembalian, Id_buku, Id_peminjaman) 
                  VALUES ('$Tgl_pinjam', '$tgl_aktual','$id_buku','$idp')";
        mysqli_query($koneksi, $r_query);

        // Stok hanya bertambah jika buku TIDAK rusak
        if ($kondisi_buku !== 'rusak') {
            mysqli_query($koneksi, "UPDATE buku SET Stok = Stok + $jumlah WHERE Id_buku = '$id_buku'");
        }

        $dt = number_format($denda_terlambat, 0, ',', '.');
        $dr = number_format($denda_rusak, 0, ',', '.');
        $dtotal = number_format($denda_total, 0, ',', '.');

        if ($kondisi_buku === 'rusak') {
            $log_query = "INSERT INTO log_stok (Id_peminjaman, stok_berkurang, Id_buku, keterangan) 
                  VALUES ('$idp', '$jumlah','$id_buku','$kondisi_buku')";
            mysqli_query($koneksi, $log_query);

            if ($hari_terlambat > 0) {
                $pesan = "Buku RUSAK dan TERLAMBAT $hari_terlambat hari.\\nDenda terlambat : Rp $dt\\nDenda rusak     : Rp $dr\\nTotal denda     : Rp $dtotal\\nStok TIDAK bertambah.";
            } else {
                $pesan = "Buku dikembalikan RUSAK.\\nDenda kerusakan : Rp $dr\\nStok TIDAK bertambah.";
            }
        } elseif ($hari_terlambat > 0) {
            $pesan = "Buku TERLAMBAT $hari_terlambat hari.\\nDenda : Rp $dt (maks. 50% harga buku)";
        } else {
            $pesan = "Buku berhasil dikembalikan tepat waktu dan kondisi baik!";
        }

        echo "<script>alert('$pesan'); window.location.href='Peminjaman.php';</script>";
    } else {
        echo "<script>alert('Gagal memproses pengembalian!'); window.location.href='Peminjaman.php';</script>";
    }
}

// Cek otomatis status Terlambat
mysqli_query($koneksi, "
    UPDATE peminjaman SET Status = 'Terlambat'
    WHERE Status = 'Dipinjam' AND Tgl_kembali < CURDATE()
");
?>

<link rel="stylesheet" href="Transaksi.css" />

<div class="container">
    <div class="page-inner">
        <div class="page-header">
            <ul class="breadcrumbs mb-3">
                <li class="nav-home"><a href="../index.php"><i class="icon-home"></i></a></li>
                <li class="separator"><i class="icon-arrow-right"></i></li>
                <li class="nav-item"><a href="#">Transaksi</a></li>
                <li class="separator"><i class="icon-arrow-right"></i></li>
                <li class="nav-item"><a href="#">Peminjaman</a></li>
            </ul>
        </div>

        <!-- KOLEKSI BUKU -->
        <div class="perpus-library">
            <div class="library-header">
                <h2>Koleksi Buku</h2>
                <?php
                $countRow = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as total FROM buku"));
                ?>
                <span class="book-count"><?= $countRow['total'] ?> Buku</span>
            </div>
            <div class="books-grid">
                <?php
                $qBuku = mysqli_query($koneksi, "
                    SELECT b.*, g.genre, k.kategori
                    FROM buku b
                    LEFT JOIN genre    g ON b.Id_genre    = g.Id_genre
                    LEFT JOIN kategori k ON b.Id_kategori = k.Id_kategori
                ");
                while ($buku = mysqli_fetch_assoc($qBuku)):
                    $stokClass = $buku['Stok'] < 10 ? 'low' : '';
                    $foto = !empty($buku['Foto_buku'])
                        ? '../Gambar/buku/' . $buku['Foto_buku']
                        : '../image/no-cover.jpg';
                    $dataJson = json_encode($buku, JSON_HEX_APOS | JSON_HEX_TAG | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE);
                    ?>
                    <div class="book-card" data-buku='<?= $dataJson ?>' onclick="openModal(this)">
                        <img src="<?= htmlspecialchars($foto) ?>" alt="<?= htmlspecialchars($buku['Judul']) ?>"
                            onerror="this.onerror=null;this.src='../image/no-cover.jpg'">
                        <div class="book-overlay">
                            <div class="book-title"><?= htmlspecialchars($buku['Judul']) ?></div>
                            <div class="book-author"><?= htmlspecialchars($buku['Pengarang']) ?></div>
                            <div class="book-stok <?= $stokClass ?>">Stok: <?= $buku['Stok'] ?></div>
                        </div>
                        <span class="hover-btn">Pinjam Buku</span>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>

        <!-- TABEL PEMINJAMAN -->
        <div class="row mt-3">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="card-title">Data Peminjaman</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="basic-datatables" class="display table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>ID Peminjaman</th>
                                        <th>Nama Peminjam</th>
                                        <th>Alamat</th>
                                        <th>No Telepon</th>
                                        <th>Tanggal Pinjam</th>
                                        <th>Tanggal Kembali(Rencana)</th>
                                        <th>Buku</th>
                                        <th>Status</th>
                                        <th>Kondisi</th>
                                        <th>Denda</th>
                                        <th>Petugas(Pinjam)</th>
                                        <th>Petugas(Kembali)</th>
                                        <th class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="table-light">
                                    <?php
                                    $qPinjam = $koneksi->query(" SELECT p.*, b.Judul, b.Id_buku, b.Harga FROM peminjaman p INNER JOIN buku b ON p.Id_buku = b.Id_buku
                                    ORDER BY p.Id_peminjaman ASC
                                    ");
                                    $no = 1;
                                    foreach ($qPinjam as $data):
                                        // Badge status
                                        if ($data['Status'] === 'Dikembalikan' && $data['Kondisi_kembali'] === 'rusak') {
                                            $statusBadge = '<span class="badge bg-secondary">Dikembalikan (Rusak)</span>';
                                        } elseif ($data['Status'] === 'Dikembalikan') {
                                            $statusBadge = '<span class="badge bg-success">Dikembalikan</span>';
                                        } elseif ($data['Status'] === 'Terlambat') {
                                            // Status ini hanya muncul untuk yang BELUM dikembalikan tapi sudah lewat tanggal
                                            $statusBadge = '<span class="badge bg-danger">Terlambat</span>';
                                        } else {
                                            $statusBadge = '<span class="badge bg-warning text-dark">Dipinjam</span>';
                                        }

                                        // Badge kondisi
                                        $kondisi = $data['Kondisi_kembali'] ?? '-';
                                        if ($kondisi === 'rusak') {
                                            $kondisiBadge = '<span class="badge bg-danger">Rusak</span>';
                                        } elseif ($kondisi === 'baik') {
                                            $kondisiBadge = '<span class="badge bg-success">Baik</span>';
                                        } else {
                                            $kondisiBadge = '<span class="text-muted">-</span>';
                                        }

                                        // Estimasi denda berjalan (belum dikembalikan)
                                        $hari_terlambat = 0;
                                        $sudah_kembali = !empty($data['Id_pengembalian']);
                                        if (!$sudah_kembali && !empty($data['Tgl_kembali'])) {
                                            $tp = new DateTime($data['Tgl_kembali']);
                                            $th = new DateTime(date('Y-m-d'));
                                            if ($th > $tp) {
                                                $hari_terlambat = (int) $th->diff($tp)->days;
                                            }
                                        }

                                        // Tampilan denda
                                        if (!empty($data['Denda']) && $data['Denda'] > 0) {
                                            $denda_tampil = '<span class="text-danger fw-bold">Rp ' . number_format($data['Denda'], 0, ',', '.') . '</span>';
                                        } elseif ($hari_terlambat > 0) {
                                            $batas_est = $data['Harga'] / 2;
                                            $est = number_format(min($hari_terlambat * 5000, $batas_est), 0, ',', '.');
                                            $denda_tampil = '<span class="text-danger">~Rp ' . $est . '</span><br><small class="text-muted">(' . $hari_terlambat . ' hari)</small>';
                                        } else {
                                            $denda_tampil = '<span class="text-muted">-</span>';
                                        }
                                        ?>
                                        <tr>
                                            <td><?= htmlspecialchars($data['Id_peminjaman']) ?></td>
                                            <td><?= htmlspecialchars($data['Nama_peminjam']) ?></td>
                                            <td><?= htmlspecialchars($data['Alamat']) ?></td>
                                            <td><?= htmlspecialchars($data['No_Telp']) ?></td>
                                            <td><?= htmlspecialchars($data['Tgl_pinjam']) ?></td>
                                            <td><?= htmlspecialchars($data['Tgl_kembali']) ?></td>
                                            <td><?= htmlspecialchars($data['Judul']) ?></td>
                                            <td><?= $statusBadge ?></td>
                                            <td><?= $kondisiBadge ?></td>
                                            <td><?= $denda_tampil ?></td>
                                            <td><?= htmlspecialchars($data['Idp_pinjam']) ?></td>
                                            <td><?= htmlspecialchars($data['Idp_kembali']) ?></td>
                                            <td class="text-center">
                                                <?php if ($_SESSION['Role'] == 'Admin'): ?>
                                                    <button type="button" class="btn btn-link btn-primary btn-sm"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#edit<?= $data['Id_peminjaman'] ?>">
                                                        <i class="fa fa-edit fa-lg"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-link btn-danger btn-sm"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#hapus<?= $data['Id_peminjaman'] ?>">
                                                        <i class="fa fa-trash fa-lg"></i>
                                                    </button>
                                                <?php endif; ?>
                                                <?php if (!$sudah_kembali): ?>
                                                    <button type="button" class="btn btn-link btn-success btn-sm"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#kembali<?= $data['Id_peminjaman'] ?>"
                                                        title="Kembalikan Buku">
                                                        <i class="fa fa-undo fa-lg"></i>
                                                    </button>
                                                <?php else: ?>
                                                    <button class="btn btn-link btn-secondary btn-sm" disabled>
                                                        <i class="fa fa-check-circle fa-lg text-success"></i>
                                                    </button>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>

                            <?php
                            $qPinjam2 = $koneksi->query("
                                SELECT p.*, b.Judul, b.Id_buku, b.Harga
                                FROM peminjaman p
                                INNER JOIN buku b ON p.Id_buku = b.Id_buku
                                ORDER BY p.Id_peminjaman ASC
                            ");
                            foreach ($qPinjam2 as $data):
                                $sudah_kembali2 = !empty($data['Id_pengembalian']);

                                // Hitung terlambat untuk modal
                                $hari_terlambat_modal = 0;
                                $denda_terlambat_modal = 0;
                                if (!empty($data['Tgl_kembali'])) {
                                    $tp2 = new DateTime($data['Tgl_kembali']);
                                    $th2 = new DateTime(date('Y-m-d'));
                                    if ($th2 > $tp2) {
                                        $hari_terlambat_modal = (int) $th2->diff($tp2)->days;
                                        $batas_modal = $data['Harga'] / 2;
                                        $denda_terlambat_modal = min($hari_terlambat_modal * 5000, $batas_modal);
                                    }
                                }
                                ?>

                                <!-- ===== MODAL EDIT ===== -->
                                <div class="modal fade" id="edit<?= $data['Id_peminjaman'] ?>" tabindex="-1"
                                    aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <form action="Peminjaman.php" method="POST">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Form Edit Data Peminjaman</h5>
                                                    <button type="button" class="btn-close"
                                                        data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <input type="hidden" name="Idp" value="<?= $data['Id_peminjaman'] ?>">
                                                    <div class="mb-3">
                                                        <label class="form-label">Nama Peminjam</label>
                                                        <input type="text" class="form-control" name="Nama_peminjam"
                                                            value="<?= htmlspecialchars($data['Nama_peminjam']) ?>">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Alamat</label>
                                                        <input type="text" class="form-control" name="Alamat"
                                                            value="<?= htmlspecialchars($data['Alamat']) ?>">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">No. Telepon</label>
                                                        <input type="text" class="form-control" name="No_Telp"
                                                            value="<?= htmlspecialchars($data['No_Telp']) ?>">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Tgl Pinjam</label>
                                                        <input type="date" class="form-control" name="Tgl_pinjam"
                                                            value="<?= htmlspecialchars($data['Tgl_pinjam']) ?>">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Tgl Kembali (Rencana)</label>
                                                        <input type="date" class="form-control" name="Tgl_kembali"
                                                            value="<?= htmlspecialchars($data['Tgl_kembali'] ?? '') ?>">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Judul</label>
                                                        <select class="form-select" name="Id_buku" id="buku"
                                                            class="form-control"><?php
                                                            $bukus->data_seek(0);
                                                            foreach ($bukus as $g): ?>
                                                                <option value="<?= $g['Id_buku'] ?>"
                                                                    <?= $data['Id_buku'] == $g['Id_buku'] ? 'selected' : '' ?>>
                                                                    <?= htmlspecialchars($g['Judul']) ?>
                                                                </option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-bs-dismiss="modal">Tutup</button>
                                                    <button type="submit" class="btn btn-primary"
                                                        name="update">Simpan</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                                <!-- ===== MODAL HAPUS ===== -->
                                <div class="modal fade" id="hapus<?= $data['Id_peminjaman'] ?>" tabindex="-1"
                                    aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <form action="Peminjaman.php" method="POST">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Konfirmasi Hapus</h5>
                                                    <button type="button" class="btn-close"
                                                        data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body text-center">
                                                    <input type="hidden" name="idp" value="<?= $data['Id_peminjaman'] ?>">
                                                    <i class="fas fa-trash text-danger mb-4" style="font-size:60px;"></i>
                                                    <h5>Apakah anda yakin ingin menghapus data?</h5>
                                                    <label>Nama Peminjam :
                                                        <b><?= htmlspecialchars($data['Nama_peminjam']) ?></b></label>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-bs-dismiss="modal">Tutup</button>
                                                    <button type="submit" class="btn btn-danger"
                                                        name="delete">Hapus</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                                <!-- ===== MODAL KEMBALIKAN ===== -->
                                <?php if (!$sudah_kembali2): ?>
                                    <div class="modal fade" id="kembali<?= $data['Id_peminjaman'] ?>" tabindex="-1"
                                        aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <form action="Peminjaman.php" method="POST">

                                                    <div
                                                        class="modal-header <?= $hari_terlambat_modal > 0 ? 'bg-danger' : 'bg-success' ?> text-white">
                                                        <h5 class="modal-title">
                                                            <i class="fas fa-undo me-2"></i>
                                                            <?= $hari_terlambat_modal > 0 ? 'Pengembalian Terlambat!' : 'Konfirmasi Pengembalian' ?>
                                                        </h5>
                                                        <button type="button" class="btn-close btn-close-white"
                                                            data-bs-dismiss="modal"></button>
                                                    </div>

                                                    <div class="modal-body">
                                                        <!-- Hidden fields -->
                                                        <input type="hidden" name="idp_kembali"
                                                            value="<?= $data['Id_peminjaman'] ?>">
                                                        <input type="hidden" name="id_buku_kembali"
                                                            value="<?= $data['Id_buku'] ?>">
                                                        <input type="hidden" name="jumlah_kembali"
                                                            value="<?= $data['Jumlah'] ?? 1 ?>">
                                                        <input type="hidden" name="tgl_kembali_plan"
                                                            value="<?= htmlspecialchars($data['Tgl_kembali'] ?? '') ?>">
                                                        <input type="hidden" name="harga_buku_kembali"
                                                            value="<?= $data['Harga'] ?>">
                                                        <input type="hidden" name="Tgl_pinjam"
                                                            value="<?= htmlspecialchars($data['Tgl_pinjam']) ?>">

                                                        <!-- Alert terlambat -->
                                                        <?php if ($hari_terlambat_modal > 0): ?>
                                                            <div class="alert alert-danger py-2 mb-3">
                                                                <i class="fas fa-clock me-1"></i>
                                                                <strong>Terlambat <?= $hari_terlambat_modal ?> hari!</strong><br>
                                                                Denda keterlambatan: <strong>Rp
                                                                    <?= number_format($denda_terlambat_modal, 0, ',', '.') ?></strong>
                                                            </div>
                                                        <?php endif; ?>

                                                        <!-- Detail peminjaman -->
                                                        <table class="table table-bordered table-sm">
                                                            <tr>
                                                                <td class="fw-bold" width="45%">Peminjam</td>
                                                                <td><?= htmlspecialchars($data['Nama_peminjam']) ?></td>
                                                            </tr>
                                                            <tr>
                                                                <td class="fw-bold">Buku</td>
                                                                <td><?= htmlspecialchars($data['Judul']) ?></td>
                                                            </tr>
                                                            <tr>
                                                                <td class="fw-bold">Harga Buku</td>
                                                                <td>Rp <?= number_format($data['Harga'], 0, ',', '.') ?></td>
                                                            </tr>
                                                            <tr>
                                                                <td class="fw-bold">Tgl Pinjam</td>
                                                                <td><?= htmlspecialchars($data['Tgl_pinjam']) ?></td>
                                                            </tr>
                                                            <tr>
                                                                <td class="fw-bold">Tgl Kembali (Rencana)</td>
                                                                <td><?= htmlspecialchars($data['Tgl_kembali'] ?? '-') ?></td>
                                                            </tr>
                                                            <tr>
                                                                <td class="fw-bold">Tgl Kembali (Aktual)</td>
                                                                <td
                                                                    class="<?= $hari_terlambat_modal > 0 ? 'text-danger' : 'text-success' ?> fw-bold">
                                                                    <?= date('d-m-Y') ?> (Hari ini)
                                                                </td>
                                                            </tr>
                                                            <?php if ($hari_terlambat_modal > 0): ?>
                                                                <tr class="table-warning">
                                                                    <td class="fw-bold">Denda Terlambat</td>
                                                                    <td class="text-danger fw-bold">
                                                                        Rp <?= number_format($denda_terlambat_modal, 0, ',', '.') ?>
                                                                    </td>
                                                                </tr>
                                                            <?php endif; ?>
                                                        </table>

                                                        <!-- ===== OPSI KONDISI BUKU ===== -->
                                                        <div class="mt-3 p-3 border rounded bg-light">
                                                            <label class="form-label fw-bold mb-2">
                                                                <i class="fas fa-book me-1"></i> Kondisi Buku Saat Dikembalikan
                                                            </label>
                                                            <div class="d-flex gap-4">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="radio"
                                                                        name="kondisi_buku"
                                                                        id="baik_<?= $data['Id_peminjaman'] ?>" value="baik"
                                                                        checked
                                                                        onchange="hitungDenda('<?= $data['Id_peminjaman'] ?>', <?= $denda_terlambat_modal ?>, <?= (int) $data['Harga'] ?>)">
                                                                    <label class="form-check-label text-success fw-bold"
                                                                        for="baik_<?= $data['Id_peminjaman'] ?>">
                                                                        <i class="fas fa-check-circle"></i> Baik
                                                                    </label>
                                                                </div>
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="radio"
                                                                        name="kondisi_buku"
                                                                        id="rusak_<?= $data['Id_peminjaman'] ?>" value="rusak"
                                                                        onchange="hitungDenda('<?= $data['Id_peminjaman'] ?>', <?= $denda_terlambat_modal ?>, <?= (int) $data['Harga'] ?>)">
                                                                    <label class="form-check-label text-danger fw-bold"
                                                                        for="rusak_<?= $data['Id_peminjaman'] ?>">
                                                                        <i class="fas fa-times-circle"></i> Rusak
                                                                    </label>
                                                                </div>
                                                            </div>

                                                            <!-- Peringatan buku rusak -->
                                                            <div id="warn_rusak_<?= $data['Id_peminjaman'] ?>"
                                                                class="alert alert-warning py-2 mt-2 d-none">
                                                                <i class="fas fa-exclamation-triangle me-1"></i>
                                                                Buku rusak — denda sebesar harga buku dan
                                                                <strong>stok tidak bertambah</strong>.
                                                            </div>

                                                            <!-- Total denda -->
                                                            <div id="box_total_<?= $data['Id_peminjaman'] ?>"
                                                                class="alert alert-danger py-2 mt-2 d-none">
                                                                <i class="fas fa-money-bill-wave me-1"></i>
                                                                Total Denda yang Harus Dibayar:
                                                                <strong id="val_total_<?= $data['Id_peminjaman'] ?>"></strong>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-bs-dismiss="modal">Batal</button>
                                                        <button type="submit" name="kembalikan"
                                                            class="btn <?= $hari_terlambat_modal > 0 ? 'btn-danger' : 'btn-success' ?>">
                                                            <i class="fas fa-check me-1"></i> Konfirmasi Pengembalian
                                                        </button>
                                                    </div>

                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- ===== MODAL PINJAM BUKU ===== -->
<div class="modal-overlay" id="modalOverlay" onclick="closeModalOutside(event)">
    <div class="modal-box" id="modalBox">
        <div class="modal-head">
            <h5><i class="fas fa-book-open" style="color:var(--accent);margin-right:8px"></i>Form Peminjaman Buku</h5>
            <button class="modal-close" onclick="closeModal()">&#x2715;</button>
        </div>
        <form action="Peminjaman.php" method="POST">
            <div class="modal-body-inner">
                <div class="book-preview">
                    <img id="modal-foto" src="" alt="Cover">
                    <div class="book-preview-info">
                        <h6 id="modal-judul"></h6>
                        <p id="modal-pengarang"></p>
                        <div>
                            <span class="badge-tag" id="modal-genre"></span>
                            <span class="badge-tag" id="modal-kategori"></span>
                        </div>
                        <div class="stok-info" id="modal-stok-info"></div>
                    </div>
                </div>

                <input type="hidden" name="id_buku" id="modal-id-buku">

                <div class="form-group-custom">
                    <label>ID Peminjaman</label>
                    <input type="text" name="Id_Peminjaman" id="Id_Peminjaman" value="<?= $kodepinjam ?>" readonly>
                </div>
                <div class="form-group-custom">
                    <label>NIK</label>
                    <input type="text" name="NIK" id="modal-nik" placeholder="16 digit NIK" maxlength="16"
                        pattern="\d{16}" title="NIK harus 16 digit angka" required>
                </div>
                <div class="form-group-custom">
                    <label>Nama Peminjam</label>
                    <input type="text" name="Nama_peminjam" id="modal-nama" placeholder="Nama lengkap" required>
                </div>
                <div class="form-group-custom">
                    <label>Alamat</label>
                    <input type="text" name="Alamat" id="modal-alamat" placeholder="Alamat peminjam" required>
                </div>
                <div class="form-group-custom">
                    <label>No. Telepon</label>
                    <input type="text" name="No_Telp" id="modal-notelp" placeholder="08xxxxxxxxxx" required>
                </div>
                <div class="form-row-custom">
                    <div class="form-group-custom">
                        <label>Tanggal Pinjam</label>
                        <input type="date" name="tgl_pinjam" id="modal-tgl-pinjam" required
                            value="<?= date('Y-m-d') ?>">
                    </div>
                    <div class="form-group-custom">
                        <label>Tanggal Kembali</label>
                        <input type="date" name="tgl_kembali" id="modal-tgl-kembali" required
                            value="<?= date('Y-m-d', strtotime('+7 days')) ?>">
                    </div>
                </div>
                <div class="form-group-custom">
                    <label>Jumlah Pinjam</label>
                    <input type="number" name="jumlah" id="modal-jumlah" min="1" value="1" required>
                </div>
            </div>
            <div class="modal-foot">
                <button type="button" class="btn-cancel" onclick="closeModal()">Batal</button>
                <button type="submit" name="pinjam" class="btn-pinjam">
                    <i class="fas fa-check me-1"></i> Konfirmasi Peminjaman
                </button>
            </div>
        </form>
    </div>
</div>


<script>
    // ============================================================
    // Hitung denda dinamis berdasarkan kondisi buku (Baik / Rusak)
    // ============================================================
    function formatRp(angka) {
        return 'Rp ' + parseInt(angka).toLocaleString('id-ID');
    }

    function hitungDenda(id, dendaTerlambat, hargaBuku) {
        var isRusak = document.getElementById('rusak_' + id).checked;
        var dendaRusak = isRusak ? hargaBuku : 0;
        var batasTerlambat = hargaBuku / 2;
        var dendaTerlambatFinal = Math.min(dendaTerlambat, batasTerlambat);
        var total = dendaTerlambatFinal + dendaRusak;

        var warnRusak = document.getElementById('warn_rusak_' + id);
        var boxTotal = document.getElementById('box_total_' + id);
        var valTotal = document.getElementById('val_total_' + id);

        // Tampilkan / sembunyikan peringatan stok rusak
        if (isRusak) {
            warnRusak.classList.remove('d-none');
        } else {
            warnRusak.classList.add('d-none');
        }

        // Tampilkan total denda jika ada
        if (total > 0) {
            boxTotal.classList.remove('d-none');
            valTotal.textContent = formatRp(total);
        } else {
            boxTotal.classList.add('d-none');
        }
    }

    // ============================================================
    // Modal pinjam buku
    // ============================================================
    function openModal(el) {
        var buku;
        try {
            buku = JSON.parse(el.getAttribute('data-buku'));
        } catch (e) {
            alert('Error membaca data buku.');
            return;
        }

        var today = new Date().toISOString().split('T')[0];
        var tglPinjam = document.querySelector('input[name="tgl_pinjam"]');
        tglPinjam.value = today;
        tglPinjam.min = today;
        tglPinjam.max = today;
        tglPinjam.readOnly = true;

        var tglKembali = document.querySelector('input[name="tgl_kembali"]');
        var d = new Date();
        d.setDate(d.getDate() + 7);
        tglKembali.value = d.toISOString().split('T')[0];
        tglKembali.min = today;

        document.getElementById('modal-id-buku').value = buku.Id_buku;
        document.getElementById('modal-judul').textContent = buku.Judul;
        document.getElementById('modal-pengarang').textContent = buku.Pengarang;
        document.getElementById('modal-genre').textContent = buku.genre || '-';
        document.getElementById('modal-kategori').textContent = buku.kategori || '-';

        var foto = buku.Foto_buku ? '../Gambar/buku/' + buku.Foto_buku : '../image/no-cover.jpg';
        var imgEl = document.getElementById('modal-foto');
        imgEl.src = foto;
        imgEl.onerror = function () { this.onerror = null; this.src = '../image/no-cover.jpg'; };

        var stok = parseInt(buku.Stok) || 0;
        var stokEl = document.getElementById('modal-stok-info');
        stokEl.textContent = '● Stok tersedia: ' + stok;
        stokEl.className = 'stok-info ' + (stok < 10 ? 'low' : 'ok');
        document.getElementById('modal-jumlah').max = stok;

        // Reset field NIK setiap kali modal dibuka
        document.getElementById('modal-nik').value = '';

        document.getElementById('modalOverlay').classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    function closeModal() {
        document.getElementById('modalOverlay').classList.remove('active');
        document.body.style.overflow = '';
    }

    function closeModalOutside(e) {
        if (e.target === document.getElementById('modalOverlay')) closeModal();
    }

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') closeModal();
    });
</script>

<?php include "../footer.php"; ?>