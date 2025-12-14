<?php
require_once __DIR__ . '/../models/Pemilih.php';
require_once __DIR__ . '/../models/Vote.php'; // <--- [1] WAJIB ADA INI

class PemilihController
{
    public function index()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        session_unset();
        session_destroy();
        $this->view('pemilih/registrasi');
    }

    // FUNGSI UTAMA (INIT)
    public function welcome()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        // 1. Cek Apakah Login
        if (!isset($_SESSION['pemilih_id'])) {
            header("Location: " . BASE_URL);
            exit();
        }

        $pemilihModel = new Pemilih();
        $user = $pemilihModel->find($_SESSION['pemilih_id']);

        if (!$user) {
            header("Location: " . BASE_URL);
            exit();
        }

        // -----------------------------------------------------------
        // LOGIKA PENENTU HALAMAN
        // -----------------------------------------------------------

        // KASUS A: JIKA SUDAH MEMILIH (Status Vote = 1)
        if ($user['status_vote'] == 1) {

            // [2] PANGGIL MODEL VOTE
            $voteModel = new Vote();

            // [3] AMBIL DATA PILIHAN DARI DATABASE
            // Fungsi ini harus ada di models/Vote.php
            $daftar_pilihan = $voteModel->getPilihanByPemilih($_SESSION['pemilih_id']);

            // [4] KIRIM DATA KE VIEW
            $data = [
                'pemilih' => $user,
                'pilihan' => $daftar_pilihan // <--- INI KUNCINYA
            ];

            // TAMPILKAN HALAMAN TERIMA KASIH
            // Pastikan nama filenya 'terima_kasih.php' di folder 'views/pemilih/'
            $this->view('pemilih/terima_kasih', $data);
        } else {
            // KASUS B: JIKA BELUM MEMILIH
            // Tampilkan halaman sambutan / scan
            $this->view('pemilih/registrasi', ['pemilih' => $user]);
        }
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
