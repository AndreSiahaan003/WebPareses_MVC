<?php

// 86400 detik = 24 Jam. durasi server reload
$durasi = 86400;

ini_set('session.gc_maxlifetime', $durasi);

session_set_cookie_params($durasi);


if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// --- 1. KONFIGURASI BASE URL ---
define('BASE_URL', 'http://localhost/webpareses');

// --- 2. LOAD CORE CLASSES (MANUAL) ---
// Memastikan database dan model dasar selalu siap
if (file_exists('./models/Database.php')) {
    require_once './models/Database.php';
}
if (file_exists('./models/Model.php')) {
    require_once './models/Model.php';
}

// --- 3. AUTOLOAD MODELS LAINNYA ---
spl_autoload_register(function ($className) {
    if (file_exists('./models/' . $className . '.php')) {
        require_once './models/' . $className . '.php';
    }
});

// --- 4. LOGIKA ROUTING ---

// Ambil URL dari parameter .htaccess
$url = isset($_GET['url']) ? rtrim($_GET['url'], '/') : '';
$url = filter_var($url, FILTER_SANITIZE_URL);
$urlParts = explode('/', $url);

// Controller Default (Halaman Depan)
$controllerName = 'PemilihController';
$methodName = 'index';
$params = [];

// Cek Controller dari URL (Segmen 1)
if (!empty($urlParts[0])) {

    // ▼▼▼ KHUSUS ROUTING AUTH (SCAN BARCODE) ▼▼▼
    // Kita paksa arahkan ke AuthController jika URL diawali 'auth'
    if ($urlParts[0] == 'auth') {
        $controllerName = 'AuthController';
    }
    // ▲▲▲ BATAS TAMBAHAN ▲▲▲

    // LOGIKA UMUM (DYNAMIC)
    else {
        // Ubah 'admin' jadi 'AdminController', 'vote' jadi 'VoteController'
        $controllerName = ucfirst($urlParts[0]) . 'Controller';
    }

    // Cek apakah file controller fisik ada?
    if (file_exists('./controllers/' . $controllerName . '.php')) {
        unset($urlParts[0]);
    } else {
        // 404 Not Found
        http_response_code(404);
        echo "<div style='text-align:center; margin-top:50px; font-family:sans-serif;'>";
        echo "<h1>404</h1><h3>Halaman tidak ditemukan.</h3>";
        echo "<p>Controller <b>$controllerName</b> tidak ditemukan di sistem.</p>";
        echo "<a href='" . BASE_URL . "' style='padding:10px 20px; background:#ddd; text-decoration:none; border-radius:5px;'>Kembali ke Beranda</a>";
        echo "</div>";
        exit;
    }
}

// Muat File Controller
require_once './controllers/' . $controllerName . '.php';
$controller = new $controllerName();

// Cek Method dari URL (Segmen 2)
if (isset($urlParts[1])) {
    if (method_exists($controller, $urlParts[1])) {
        $methodName = $urlParts[1];
        unset($urlParts[1]);
    } else {
        // Jika method tidak ada, tampilkan error
        http_response_code(404);
        echo "<h1>404</h1><h3>Fungsi tidak ditemukan.</h3>";
        exit;
    }
}

// Ambil Parameter Sisa (Segmen 3 dst) sebagai argumen fungsi
$params = $urlParts ? array_values($urlParts) : [];

// Eksekusi Controller/Method
try {
    call_user_func_array([$controller, $methodName], $params);
} catch (Exception $e) {
    echo '<div style="color:red; font-weight:bold; padding:20px;">System Error: ' . $e->getMessage() . '</div>';
}
