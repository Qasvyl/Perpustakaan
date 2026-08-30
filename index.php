<?php
session_start();
include "koneksi.php";
include "auth.php";
cekLogin();
include "header.php";

// ============================================================
// KARTU STATISTIK
// ============================================================
$qJumlahBuku = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as total FROM buku"));
$jumlah_buku = $qJumlahBuku['total'];

$qTotalTransaksi = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as total FROM peminjaman"));
$total_transaksi = $qTotalTransaksi['total'];

$qPeminjaman = mysqli_fetch_assoc(mysqli_query($koneksi, "
    SELECT COUNT(*) as total FROM peminjaman WHERE Status IN ('Dipinjam','Terlambat')
"));
$jumlah_peminjaman = $qPeminjaman['total'];

$qPengembalian = mysqli_fetch_assoc(mysqli_query($koneksi, "
    SELECT COUNT(*) as total FROM peminjaman WHERE Status IN ('Dikembalikan','Dikembalikan (Rusak)')
"));
$jumlah_pengembalian = $qPengembalian['total'];

// ============================================================
// STATISTIK — range tanggal 
// ============================================================
$tgl_akhir = isset($_GET['tgl_akhir']) ? $_GET['tgl_akhir'] : date('Y-m-d');
$tgl_awal = isset($_GET['tgl_awal']) ? $_GET['tgl_awal'] : date('Y-m-d', strtotime('-6 days'));

if ($tgl_awal > $tgl_akhir) {
  $tmp = $tgl_awal;
  $tgl_awal = $tgl_akhir;
  $tgl_akhir = $tmp;
}

$labels = [];
$data_pinjam = [];
$data_kembali = [];

$current = new DateTime($tgl_awal);
$end = new DateTime($tgl_akhir);

$qStatPinjam = mysqli_query($koneksi, "
    SELECT DATE(Tgl_pinjam) as tgl, COUNT(*) as total
    FROM peminjaman
    WHERE DATE(Tgl_pinjam) BETWEEN '$tgl_awal' AND '$tgl_akhir'
    GROUP BY DATE(Tgl_pinjam)
");
$mapPinjam = [];
while ($r = mysqli_fetch_assoc($qStatPinjam)) {
  $mapPinjam[$r['tgl']] = (int) $r['total'];
}

$qStatKembali = mysqli_query($koneksi, "
    SELECT DATE(Tgl_kembali) as tgl, COUNT(*) as total
    FROM peminjaman
    WHERE Status IN ('Dikembalikan','Dikembalikan (Rusak)','Terlambat')
      AND DATE(Tgl_kembali) BETWEEN '$tgl_awal' AND '$tgl_akhir'
    GROUP BY DATE(Tgl_kembali)
");
$mapKembali = [];
while ($r = mysqli_fetch_assoc($qStatKembali)) {
  $mapKembali[$r['tgl']] = (int) $r['total'];
}

while ($current <= $end) {
  $tgl = $current->format('Y-m-d');
  $labels[] = $current->format('d/m');
  $data_pinjam[] = $mapPinjam[$tgl] ?? 0;
  $data_kembali[] = $mapKembali[$tgl] ?? 0;
  $current->modify('+1 day');
}

$labels_json = json_encode($labels);
$pinjam_json = json_encode($data_pinjam);
$kembali_json = json_encode($data_kembali);
$total_pinjam_range = array_sum($data_pinjam);
$total_kembali_range = array_sum($data_kembali);
?>

<head><title>Beranda</title></head>

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
                  <div class="dropdown-divider"></div>
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
      <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
        <div>
          <h3 class="fw-bold mb-3">Beranda</h3>
          <h6 class="op-7 mb-2">Sistem Informasi Perpustakaan</h6>
        </div>
        <div class="ms-md-auto py-2 py-md-0">
          <a href="Transaksi/Peminjaman.php" class="btn btn-primary btn-round">
            <i class="fas fa-plus me-1"></i> Tambah Peminjaman
          </a>
        </div>
      </div>

      <!-- ===== KARTU STATISTIK ===== -->
      <div class="row">
        <div class="col-sm-6 col-md-3">
          <div class="card card-stats card-round">
            <div class="card-body">
              <div class="row align-items-center">
                <div class="col-icon">
                  <div class="icon-big text-center icon-primary bubble-shadow-small">
                    <i class="fas fa-book"></i>
                  </div>
                </div>
                <div class="col col-stats ms-3 ms-sm-0">
                  <div class="numbers">
                    <p class="card-category">Jumlah Buku</p>
                    <h4 class="card-title"><?= number_format($jumlah_buku, 0, ',', '.') ?></h4>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-sm-6 col-md-3">
          <div class="card card-stats card-round">
            <div class="card-body">
              <div class="row align-items-center">
                <div class="col-icon">
                  <div class="icon-big text-center icon-info bubble-shadow-small">
                    <i class="fas fa-exchange-alt"></i>
                  </div>
                </div>
                <div class="col col-stats ms-3 ms-sm-0">
                  <div class="numbers">
                    <p class="card-category">Total Transaksi</p>
                    <h4 class="card-title"><?= number_format($total_transaksi, 0, ',', '.') ?></h4>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-sm-6 col-md-3">
          <div class="card card-stats card-round">
            <div class="card-body">
              <div class="row align-items-center">
                <div class="col-icon">
                  <div class="icon-big text-center icon-warning bubble-shadow-small">
                    <i class="fa fa-calendar"></i>
                  </div>
                </div>
                <div class="col col-stats ms-3 ms-sm-0">
                  <div class="numbers">
                    <p class="card-category">Dipinjam</p>
                    <h4 class="card-title"><?= number_format($jumlah_peminjaman, 0, ',', '.') ?></h4>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-sm-6 col-md-3">
          <div class="card card-stats card-round">
            <div class="card-body">
              <div class="row align-items-center">
                <div class="col-icon">
                  <div class="icon-big text-center icon-success bubble-shadow-small">
                    <i class="fas fa-undo-alt"></i>
                  </div>
                </div>
                <div class="col col-stats ms-3 ms-sm-0">
                  <div class="numbers">
                    <p class="card-category">Dikembalikan</p>
                    <h4 class="card-title"><?= number_format($jumlah_pengembalian, 0, ',', '.') ?></h4>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- ===== CHART ===== -->
      <div class="row">
        <div class="col-md-8">
          <div class="card card-round">
            <div class="card-header">
              <div class="card-head-row flex-wrap gap-2">
                <div class="card-title">Statistik Peminjaman &amp; Pengembalian</div>
                <form method="GET" action="index.php" class="d-flex align-items-center gap-2 ms-auto flex-wrap">
                  <div class="d-flex align-items-center gap-1">
                    <label class="mb-0 text-muted small fw-semibold">Dari</label>
                    <input type="date" name="tgl_awal" class="form-control form-control-sm"
                      value="<?= htmlspecialchars($tgl_awal) ?>" style="width:145px;">
                  </div>
                  <div class="d-flex align-items-center gap-1">
                    <label class="mb-0 text-muted small fw-semibold">Sampai</label>
                    <input type="date" name="tgl_akhir" class="form-control form-control-sm"
                      value="<?= htmlspecialchars($tgl_akhir) ?>" style="width:145px;">
                  </div>
                  <button type="submit" class="btn btn-primary btn-sm">
                    <i class="fa fa-search me-1"></i> Tampilkan
                  </button>
                  <a href="index.php" class="btn btn-secondary btn-sm" title="Reset">
                    <i class="fa fa-undo"></i>
                  </a>
                </form>
              </div>
              <div class="mt-2 d-flex gap-3 flex-wrap">
                <small class="text-muted">
                  Periode: <strong><?= date('d M Y', strtotime($tgl_awal)) ?></strong>
                  - <strong><?= date('d M Y', strtotime($tgl_akhir)) ?></strong>
                  (<?= (new DateTime($tgl_awal))->diff(new DateTime($tgl_akhir))->days + 1 ?> hari)
                </small>
                <small style="color:#2196F3;">&#9679; Peminjaman: <strong><?= $total_pinjam_range ?></strong></small>
                <small style="color:#26a69a;">&#9679; Pengembalian: <strong><?= $total_kembali_range ?></strong></small>
              </div>
            </div>
            <div class="card-body">
              <div class="chart-container" style="min-height: 340px; position: relative;">
                <canvas id="myStatChart"></canvas>
              </div>
            </div>
          </div>
        </div>

        <div class="col-md-4">
          <div class="card card-round">
            <div class="card-body">
              <h5 class="fw-bold mb-3">Ringkasan Periode</h5>
              <div class="d-flex justify-content-between align-items-center mb-3 p-3 bg-light rounded">
                <div>
                  <p class="mb-0 text-muted small">Total Peminjaman</p>
                  <h3 class="mb-0 fw-bold" style="color:#2196F3;"><?= $total_pinjam_range ?></h3>
                </div>
                <div class="icon-big text-center icon-primary">
                  <i class="fas fa-book-open"></i>
                </div>
              </div>
              <div class="d-flex justify-content-between align-items-center p-3 bg-light rounded">
                <div>
                  <p class="mb-0 text-muted small">Total Pengembalian</p>
                  <h3 class="mb-0 fw-bold" style="color:#26a69a;"><?= $total_kembali_range ?></h3>
                </div>
                <div class="icon-big text-center icon-success">
                  <i class="fas fa-undo-alt"></i>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- ===== TABEL PEMINJAMAN TERBARU ===== -->
      <div class="row">
        <div class="col-md-12">
          <div class="card card-round">
            <div class="card-header">
              <div class="card-head-row card-tools-still-right">
                <h4 class="card-title">Peminjaman Terbaru</h4>
                <div class="card-tools">
                  <a href="Transaksi/Peminjaman.php" class="btn btn-primary btn-sm btn-round">Lihat Semua</a>
                </div>
              </div>
            </div>
            <div class="card-body p-0">
              <div class="table-responsive">
                <table class="table align-items-center mb-0">
                  <thead class="thead-light">
                    <tr>
                      <th>Nama Peminjam</th>
                      <th>Buku</th>
                      <th class="text-center">Tgl Pinjam</th>
                      <th class="text-center">Tgl Kembali</th>
                      <th class="text-center">Status</th>
                      <th class="text-center">Denda</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    $qTerbaru = mysqli_query($koneksi, "
                        SELECT p.*, b.Judul
                        FROM peminjaman p
                        INNER JOIN buku b ON p.Id_buku = b.Id_buku
                        ORDER BY p.Tgl_pinjam DESC
                        LIMIT 7
                    ");
                    while ($row = mysqli_fetch_assoc($qTerbaru)):
                      switch ($row['Status']) {
                        case 'Dikembalikan':
                          $badge = '<span class="badge badge-success">Dikembalikan</span>';
                          break;
                        case 'Dikembalikan (Rusak)':
                          $badge = '<span class="badge badge-secondary">Rusak</span>';
                          break;
                        case 'Terlambat':
                          $badge = '<span class="badge badge-danger">Terlambat</span>';
                          break;
                        default:
                          $badge = '<span class="badge badge-warning">Dipinjam</span>';
                      }
                      $denda_tampil = '-';
                      if (!empty($row['Denda']) && $row['Denda'] > 0) {
                        $denda_tampil = '<span class="text-danger fw-bold">Rp ' . number_format($row['Denda'], 0, ',', '.') . '</span>';
                      }
                      ?>
                      <tr>
                        <td><?= htmlspecialchars($row['Nama_peminjam']) ?></td>
                        <td><?= htmlspecialchars($row['Judul']) ?></td>
                        <td class="text-center"><?= htmlspecialchars($row['Tgl_pinjam']) ?></td>
                        <td class="text-center">
                          <?= $row['Tgl_kembali'] ? htmlspecialchars($row['Tgl_kembali']) : '<span class="text-muted">-</span>' ?>
                        </td>
                        <td class="text-center"><?= $badge ?></td>
                        <td class="text-center"><?= $denda_tampil ?></td>
                      </tr>
                    <?php endwhile; ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>

  <?php include "footer.php"; ?>

  <script>
    window.addEventListener('load', function () {

      var canvas = document.getElementById('myStatChart');
      if (!canvas) return;

      // Hancurkan chart lama jika ada (dari template)
      var existing = Chart.getChart ? Chart.getChart(canvas) : (canvas._chartInstance || null);
      if (existing) existing.destroy();

      var ctx = canvas.getContext('2d');

      // ── Gradient Biru ──
      var gradBlue = ctx.createLinearGradient(0, 0, 0, 320);
      gradBlue.addColorStop(0, 'rgba(33, 150, 243, 0.18)');
      gradBlue.addColorStop(1, 'rgba(33, 150, 243, 0)');

      // ── Gradient Hijau/Teal ──
      var gradTeal = ctx.createLinearGradient(0, 0, 0, 320);
      gradTeal.addColorStop(0, 'rgba(38, 166, 154, 0.18)');
      gradTeal.addColorStop(1, 'rgba(38, 166, 154, 0)');

      var myStatChart = new Chart(ctx, {
        type: 'line',
        data: {
          labels: <?= $labels_json ?>,
          datasets: [
            {
              label: 'Peminjaman',
              data: <?= $pinjam_json ?>,
              borderColor: '#2196F3',
              backgroundColor: gradBlue,
              pointBackgroundColor: '#fff',
              pointBorderColor: '#2196F3',
              pointBorderWidth: 2,
              pointRadius: 4,
              pointHoverRadius: 6,
              pointHoverBackgroundColor: '#2196F3',
              fill: true,
              tension: 0.4,
              borderWidth: 2
            },
            {
              label: 'Pengembalian',
              data: <?= $kembali_json ?>,
              borderColor: '#26a69a',
              backgroundColor: gradTeal,
              pointBackgroundColor: '#fff',
              pointBorderColor: '#26a69a',
              pointBorderWidth: 2,
              pointRadius: 4,
              pointHoverRadius: 6,
              pointHoverBackgroundColor: '#26a69a',
              fill: true,
              tension: 0.4,
              borderWidth: 2
            }
          ]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          interaction: { mode: 'index', intersect: false },
          plugins: {
            legend: {
              display: true,
              position: 'top',
              align: 'end',
              labels: {
                usePointStyle: true,
                pointStyle: 'circle',
                padding: 20,
                font: { size: 12, family: "'Helvetica Neue', Arial, sans-serif" },
                color: '#6c757d'
              }
            },
            tooltip: {
              backgroundColor: '#fff',
              titleColor: '#333',
              bodyColor: '#555',
              borderColor: '#e9ecef',
              borderWidth: 1,
              padding: 12,
              cornerRadius: 8,
              displayColors: true,
              callbacks: {
                title: function (items) {
                  return 'Tanggal: ' + items[0].label;
                },
                label: function (item) {
                  return '  ' + item.dataset.label + ': ' + item.raw + ' transaksi';
                }
              }
            }
          },
          scales: {
            x: {
              grid: {
                display: false
              },
              border: {
                display: false
              },
              ticks: {
                maxTicksLimit: 14,
                maxRotation: 0,
                color: '#adb5bd',
                font: { size: 11 }
              }
            },
            y: {
              beginAtZero: true,
              border: {
                display: false,
                dash: [4, 4]
              },
              grid: {
                color: 'rgba(0,0,0,0.06)',
                drawBorder: false
              },
              ticks: {
                stepSize: 1,
                precision: 0,
                color: '#adb5bd',
                font: { size: 11 },
                padding: 8
              }
            }
          }
        }
      });

      canvas._chartInstance = myStatChart;
    });
  </script>