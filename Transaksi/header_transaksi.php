<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>Transaksi</title>
    <meta content="width=device-width, initial-scale=1.0, shrink-to-fit=no" name="viewport" />
    <link rel="icon" href="../image/Plogo.png" type="image/x-icon" />

    <!-- Fonts and icons -->
    <script src="../assets/js/plugin/webfont/webfont.min.js"></script>
    <script>
        WebFont.load({
            google: { families: ["Public Sans:300,400,500,600,700"] },
            custom: {
                families: [
                    "Font Awesome 5 Solid",
                    "Font Awesome 5 Regular",
                    "Font Awesome 5 Brands",
                    "simple-line-icons",
                ],
                urls: ["../assets/css/fonts.min.css"],
            },
            active: function () {
                sessionStorage.fonts = true;
            },
        });
    </script>

    <!-- CSS Files -->
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css" />
    <link rel="stylesheet" href="../assets/css/plugins.min.css" />
    <link rel="stylesheet" href="../assets/css/kaiadmin.min.css" />

    <!-- CSS Just for demo purpose, don't include it in your project -->
    <link rel="stylesheet" href="../assets/css/demo.css" />
</head>

<body>
    <div class="wrapper">
        <!-- Sidebar -->
        <div class="sidebar" data-background-color="dark">
            <div class="sidebar-logo">
                <!-- Logo Header -->
                <div class="logo-header" data-background-color="dark">
                    <a href="../index.html" class="logo">
                        <img src="../image/Plogo.png" alt="navbar brand" class="navbar-brand" width="50" height="50" />
                        <h3 class="text-light mt-3">Perpus</h3>
                    </a>
                    <div class="nav-toggle">
                        <button class="btn btn-toggle toggle-sidebar">
                            <i class="gg-menu-right"></i>
                        </button>
                        <button class="btn btn-toggle sidenav-toggler">
                            <i class="gg-menu-left"></i>
                        </button>
                    </div>
                    <button class="topbar-toggler more">
                        <i class="gg-more-vertical-alt"></i>
                    </button>
                </div>
                <!-- End Logo Header -->
            </div>
            <div class="sidebar-wrapper scrollbar scrollbar-inner">
                <div class="sidebar-content">
                    <!-- Sidebar -->
                    <ul class="nav nav-secondary">

                        <!-- Beranda — semua bisa akses -->
                        <li class="nav-item">
                            <a href="../index.php">
                                <i class="fas fa-home"></i>
                                <p>Beranda</p>
                            </a>
                        </li>

                        <!-- Data Master & Data Detail — HANYA ADMIN -->
                        <?php if ($_SESSION['Role'] == 'Admin'): ?>
                            <li class="nav-section">
                                <h4 class="text-section">Components</h4>
                            </li>
                            <li class="nav-item">
                                <a data-bs-toggle="collapse" href="#base">
                                    <i class="fas fa-layer-group"></i>
                                    <p>Data Master</p>
                                    <span class="caret"></span>
                                </a>
                                <div class="collapse" id="base">
                                    <ul class="nav nav-collapse">
                                        <li><a href="../Data_Master/Data_Buku.php"><span class="sub-item">Data Buku</span></a>
                                        </li>
                                        <li><a href="../Data_Master/Data_Petugas.php"><span class="sub-item">Data
                                                    Petugas</span></a></li>
                                    </ul>
                                </div>
                            </li>
                            <li class="nav-item">
                                <a data-bs-toggle="collapse" href="#sidebarLayouts">
                                    <i class="fas fa-th-list"></i>
                                    <p>Data Detail</p>
                                    <span class="caret"></span>
                                </a>
                                <div class="collapse" id="sidebarLayouts">
                                    <ul class="nav nav-collapse">
                                        <li><a href="../Data_Details/Data_Genre.php"><span class="sub-item">Data
                                                    Genre</span></a></li>
                                        <li><a href="../Data_Details/Data_Kategori.php"><span class="sub-item">Data
                                                    Kategori</span></a></li>
                                    </ul>
                                </div>
                            </li>
                        <?php endif; ?>

                        <!-- Transaksi — semua bisa akses -->
                        <li class="nav-item">
                            <a data-bs-toggle="collapse" href="#forms">
                                <i class="fas fa-exchange-alt"></i>
                                <p>Transaksi</p>
                                <span class="caret"></span>
                            </a>
                            <div class="collapse" id="forms">
                                <ul class="nav nav-collapse">
                                    <li><a href="Peminjaman.php"><span class="sub-item">Peminjaman</span></a></li>
                                </ul>
                            </div>
                        </li>

                            <li class="nav-item">
                                <a href="../Log_Stok.php">
                                    <i class="fas fa-desktop"></i>
                                    <p>Log Stok</p>
                                </a>
                            </li>
                             <li class="nav-item">
                            <a href="../riwayat.php">
                                <i class="fas fa-desktop"></i>
                                <p>Riwayat</p>
                            </a>
                        </li>

                    </ul>
                </div>
            </div>
        </div>
        <!-- End Sidebar -->

        <div class="main-panel">
            <div class="main-header">
                <div class="main-header-logo">
                    <!-- Logo Header -->
                    <div class="logo-header" data-background-color="dark">
                        <a href="../index.html" class="logo">
                            <img src="../assets/img/kaiadmin/logo_light.svg" alt="navbar brand" class="navbar-brand"
                                height="20" />
                        </a>
                        <div class="nav-toggle">
                            <button class="btn btn-toggle toggle-sidebar">
                                <i class="gg-menu-right"></i>
                            </button>
                            <button class="btn btn-toggle sidenav-toggler">
                                <i class="gg-menu-left"></i>
                            </button>
                        </div>
                        <button class="topbar-toggler more">
                            <i class="gg-more-vertical-alt"></i>
                        </button>
                    </div>
                    <!-- End Logo Header -->
                </div>
                <!-- Navbar Header -->
                <nav class="navbar navbar-header navbar-header-transparent navbar-expand-lg border-bottom">
                    <div class="container-fluid">

                        <ul class="navbar-nav topbar-nav ms-md-auto align-items-center">
                                <ul class="dropdown-menu dropdown-search animated fadeIn">
                                    <form class="navbar-left navbar-form nav-search">
                                        <div class="input-group">
                                            <input type="text" placeholder="Search ..." class="form-control" />
                                        </div>
                                    </form>
                                </ul>
                            </li>

                            <li class="nav-item topbar-user dropdown hidden-caret">
                                <a class="dropdown-toggle profile-pic" data-bs-toggle="dropdown" 
                                    aria-expanded="false">
                                    <div class="avatar-sm">
                                        <img src="../image/user.svg" alt="..."
                                            class="avatar-img rounded-circle" />
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
                                                    <img src="../image/user.svg" alt="image profile"
                                                        class="avatar-img rounded" />
                                                </div>
                                                <div class="u-text">
                                                    <h4><?= htmlspecialchars($_SESSION['Username']) ?></h4>
                                                </div>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="dropdown-divider"></div>
                                            <a class="dropdown-item" href="../logout.php">Logout</a>
                                        </li>
                                    </div>
                                </ul>
                            </li>
                        </ul>
                    </div>
                </nav>
                <!-- End Navbar -->
            </div>