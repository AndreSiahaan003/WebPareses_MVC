<?php
// Gunakan __DIR__ untuk path absolut agar tidak error "Failed to open stream"
require_once __DIR__ . '/../models/Pemilih.php';

class AuthController
{

    public function scan()
    {
        // Ambil token dari URL (?t=xyz...)
        $token = $_GET['t'] ?? '';

        // Validasi Token Kosong
        if (empty($token)) {
            echo "<h3 style='color:red; text-align:center; margin-top:20%; font-family:sans-serif;'>Token tidak valid atau kosong.</h3>";
            exit();
        }

        $pemilihModel = new Pemilih();
        $pemilih = $pemilihModel->findByToken($token);

        // 1. Cek apakah pemilih ada di database
        if (!$pemilih) {
            echo "<div style='text-align:center; margin-top:20%; font-family:sans-serif;'>";
            echo "<h1 style='color:red; font-size:3rem;'>🚫</h1>";
            echo "<h2>TOKEN TIDAK DIKENALI</h2>";
            echo "<p>QR Code ini tidak terdaftar dalam sistem.</p>";
            echo "</div>";
            exit();
        }

        // 2. LOGIKA KUNCI: CEK APAKAH SUDAH MEMILIH?
        // Kita HANYA cek 'status_vote'. Abaikan 'token_used' di sini.
        // Tujuannya: Jika HP mati/error sebelum submit, user BISA scan ulang.
        if ($pemilih['status_vote'] == 1) {

            // HAPUS SESSION LAMA (Supaya tidak nyangkut)
            if (session_status() == PHP_SESSION_NONE) {
                session_start();
            }
            session_unset();
            session_destroy();

            // Tampilkan Halaman Error 
            require_once __DIR__ . '/../views/auth/scan_failed.php';
            exit();
        }

        // --- 3. PROSES LOGIN SUKSES ---

        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }


        // A. Set Session
        $_SESSION['pemilih_id'] = $pemilih['id'];
        $_SESSION['pemilih_nama'] = $pemilih['nama'];

        // C. REDIRECT KE HALAMAN KHUSUS MEMBER (WELCOME)
        header("Location: " . BASE_URL . "/pemilih/welcome");

        exit();
    }
}
