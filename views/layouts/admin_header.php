<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Dashboard</title>

    <link href="<?php echo BASE_URL; ?>/assets/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

    <link href="<?php echo BASE_URL; ?>/assets/css/style.css" rel="stylesheet">

    <style>
        /* Highlight menu aktif di admin */
        .navbar-nav .nav-link.active {
            background-color: rgba(255, 255, 255, 0.2);
            border-radius: 20px;
            font-weight: bold;
            color: #fff !important;
        }

        .navbar-nav .nav-link:hover {
            color: #f0f0f0 !important;
        }
    </style>
</head>

<body style="background-color: #f8f9fa;">

    <?php
    // Logika Penentuan Halaman Aktif (Sama seperti kode Anda)
    // Mengambil segmen terakhir dari URL untuk menentukan menu aktif
    $uri_segments = explode('/', parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
    $current_page = end($uri_segments);

    // Mapping halaman ke menu (agar jika ada ID di URL tetap terdeteksi)
    // Contoh: editPemilih/5 tetap dianggap menu 'pemilih'
    $active = 'dashboard'; // Default

    if (strpos($_SERVER['REQUEST_URI'], '/admin/pemilih') !== false || strpos($_SERVER['REQUEST_URI'], 'Pemilih') !== false) {
        $active = 'pemilih';
    } elseif (strpos($_SERVER['REQUEST_URI'], '/admin/pareses') !== false || strpos($_SERVER['REQUEST_URI'], 'Pareses') !== false) {
        $active = 'pareses';
    } elseif (strpos($_SERVER['REQUEST_URI'], '/admin/majelis') !== false || strpos($_SERVER['REQUEST_URI'], 'Majelis') !== false) {
        $active = 'majelis';
    } elseif (strpos($_SERVER['REQUEST_URI'], '/admin/bpk') !== false || strpos($_SERVER['REQUEST_URI'], 'BPK') !== false) {
        $active = 'bpk';
    }
    ?>

    <nav class="navbar navbar-expand-lg navbar-dark mb-5"
        style="background: linear-gradient(to right, #198754, #42d392); 
                border-bottom-left-radius: 50% 20px; 
                border-bottom-right-radius: 50% 20px; 
                box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);">

        <div class="container">

            <a class="navbar-brand d-flex align-items-center" href="<?php echo BASE_URL; ?>/admin/dashboard">
                <img src="<?php echo BASE_URL; ?>/assets/img/download.png"
                    alt="Logo"
                    width="45" height="45"
                    class="d-inline-block me-2 bg-white rounded-circle p-1 shadow-sm"
                    style="object-fit: contain;">

                <div class="d-flex flex-column">
                    <span class="fw-bold fs-5" style="text-shadow: 1px 1px 2px rgba(0,0,0,0.2);">Admin Panel</span>
                    <span class="small text-white-50" style="font-size: 0.7rem;">E-Voting System</span>
                </div>
            </a>

            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#adminNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="adminNav">
                <ul class="navbar-nav mx-auto mb-2 mb-lg-0">

                    <li class="nav-item px-1">
                        <a class="nav-link <?php echo ($active == 'dashboard') ? 'active' : ''; ?>" href="<?php echo BASE_URL; ?>/admin/dashboard">
                            <i class="bi bi-speedometer2 me-1"></i> Statistik
                        </a>
                    </li>

                    <li class="nav-item px-1">
                        <a class="nav-link <?php echo ($active == 'pemilih') ? 'active' : ''; ?>" href="<?php echo BASE_URL; ?>/admin/pemilih">
                            <i class="bi bi-people-fill me-1"></i> Data Pemilih
                        </a>
                    </li>

                    <li class="nav-item px-1">
                        <a class="nav-link <?php echo ($active == 'pareses') ? 'active' : ''; ?>" href="<?php echo BASE_URL; ?>/admin/pareses">
                            <i class="bi bi-person-badge-fill me-1"></i> Pareses
                        </a>
                    </li>

                    <li class="nav-item px-1">
                        <a class="nav-link <?php echo ($active == 'majelis') ? 'active' : ''; ?>" href="<?php echo BASE_URL; ?>/admin/majelis">
                            <i class="bi bi-building-fill me-1"></i> Majelis
                        </a>
                    </li>

                    <li class="nav-item px-1">
                        <a class="nav-link <?php echo ($active == 'bpk') ? 'active' : ''; ?>" href="<?php echo BASE_URL; ?>/admin/bpk">
                            <i class="bi bi-calculator-fill me-1"></i> BPK
                        </a>
                    </li>

                </ul>

                <div class="d-flex align-items-center text-white">
                    <div class="me-3 text-end d-none d-lg-block">
                        <small class="d-block text-white-50" style="font-size: 0.7rem;">Login Sebagai</small>
                        <span class="fw-bold"><?php echo htmlspecialchars($_SESSION['admin_username']); ?></span>
                    </div>

                    <a href="<?php echo BASE_URL; ?>/admin/logout" class="btn btn-danger btn-sm rounded-pill px-3 shadow-sm">
                        <i class="bi bi-box-arrow-right me-1"></i> Keluar
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <div class="container">