<?php
require_once __DIR__ . '/../models/Pemilih.php';

class PemilihController
{
    // --------------------------------------------------------------
    // 1. FUNGSI INDEX (URL UTAMA) -> BERFUNGSI SEBAGAI RESET/LOGOUT
    // --------------------------------------------------------------
    // Diakses saat: Buka web pertama kali, atau orang lain ketik URL utama.
    public function index()
    {
        // Mulai sesi hanya untuk menghancurkannya
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        // HAPUS SEMUA SESI (Agar bersih untuk orang berikutnya)
        session_unset();
        session_destroy();

        // Tampilkan View (Otomatis mode "Menunggu Scan" karena sesi kosong)
        $this->view('pemilih/registrasi');
    }

    // --------------------------------------------------------------
    // 2. FUNGSI WELCOME (HALAMAN MEMBER) -> REFRESH TETAP AMAN
    // --------------------------------------------------------------
    // Diakses saat: Redirect otomatis setelah Scan Barcode sukses.
    public function welcome()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        // CEK KEAMANAN 1: Apakah punya sesi login?
        if (!isset($_SESSION['pemilih_id'])) {
            // Jika tidak punya sesi (coba nembak URL), lempar ke depan (Reset)
            header("Location: " . BASE_URL);
            exit();
        }

        // CEK KEAMANAN 2: Apakah sudah pernah memilih?
        $pemilihModel = new Pemilih();
        $userData = $pemilihModel->find($_SESSION['pemilih_id']);

        // Jika user tidak ditemukan ATAU sudah vote (status=1)
        if (!$userData || $userData['status_vote'] == 1) {
            // Tendang keluar ke halaman Reset
            header("Location: " . BASE_URL);
            exit();
        }

        // Jika lolos semua cek, Tampilkan View
        // (Otomatis mode "Selamat Datang" karena sesi masih ada)
        $this->view('pemilih/registrasi');
    }

    // Helper View
    protected function view($view, $data = [])
    {
        extract($data);
        require_once "./views/layouts/header.php";
        require_once "./views/$view.php";
        require_once "./views/layouts/footer.php";
    }
}
