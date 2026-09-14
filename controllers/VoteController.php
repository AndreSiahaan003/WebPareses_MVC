<?php
// Pastikan memanggil Model yang dibutuhkan
require_once __DIR__ . '/../models/Calon.php';
require_once __DIR__ . '/../models/Vote.php';
require_once __DIR__ . '/../models/Pemilih.php';

class VoteController
{
    public function __construct()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['pemilih_id'])) {
            header("Location: " . BASE_URL);
            exit();
        }
    }

    public function index()
    {
        $calonModel = new Calon();
        $data['pareses'] = $calonModel->getAllPareses();
        $data['majelis'] = $calonModel->getAllMajelisPusat();
        $data['bpk'] = $calonModel->getAllBPK();

        // Ambil data sesi jika ada (untuk repopulate jika kembali)
        // [PERUBAHAN]: Default array kosong diubah menjadi null karena sekarang menggunakan radio button
        $data['selected'] = $_SESSION['temp_vote'] ?? [
            'pareses' => null,
            'majelis' => null,
            'bpk' => null
        ];

        $this->view('pemilih/pemilihan', $data);
    }

    public function submit()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            // --- [PERUBAHAN CRITICAL] ---
            // Data POST sekarang berupa ID tunggal (String), bukan Array lagi
            $pareses_id = $_POST['pareses'] ?? null;
            $majelis_id = $_POST['majelis'] ?? null;
            $bpk_id = $_POST['bpk'] ?? null;

            // Simpan ke Sesi Sementara
            $_SESSION['temp_vote'] = [
                'pareses' => $pareses_id,
                'majelis' => $majelis_id,
                'bpk' => $bpk_id
            ];

            // 1. Validasi Pareses (Wajib 1)
            if (empty($pareses_id)) {
                $this->setFlash('error', 'Pareses: Anda harus memilih 1 calon.');
                header("Location: " . BASE_URL . "/vote");
                return;
            }

            // 2. Validasi Majelis (Wajib 1)
            if (empty($majelis_id)) {
                $this->setFlash('error', 'Majelis Pusat: Anda harus memilih 1 calon.');
                header("Location: " . BASE_URL . "/vote");
                return;
            }

            // 3. Validasi BPK (Wajib 1)
            if (empty($bpk_id)) {
                $this->setFlash('error', 'Badan Pemeriksa Keuangan: Anda harus memilih 1 calon.');
                header("Location: " . BASE_URL . "/vote");
                return;
            }

            header("Location: " . BASE_URL . "/vote/confirm");
            exit();
        }
    }

    public function confirm()
    {
        if (!isset($_SESSION['temp_vote'])) {
            header("Location: " . BASE_URL . "/vote");
            exit();
        }
        $this->view('pemilih/konfirmasi');
    }

    public function save()
    {
        if (!isset($_SESSION['temp_vote']) || !isset($_SESSION['pemilih_id'])) {
            header("Location: " . BASE_URL);
            exit();
        }

        $voteData = $_SESSION['temp_vote'];
        $pemilih_id = $_SESSION['pemilih_id'];

        try {
            $voteModel = new Vote();

            // --- [PERUBAHAN CRITICAL] ---
            // Karena sebelumnya Model (saveVote) dirancang menerima ARRAY (banyak ID), 
            // kita bungkus ID tunggal tersebut dengan kurung siku [...] menjadi Array 
            // agar Model lama Anda tidak error saat melakukan foreach.
            $voteModel->saveVote(
                $pemilih_id,
                [$voteData['pareses']], // Dibungkus array
                [$voteData['majelis']], // Dibungkus array
                [$voteData['bpk']]      // Dibungkus array
            );

            // Hapus sesi temp_vote
            unset($_SESSION['temp_vote']);

            // Redirect ke Thanks
            header("Location: " . BASE_URL . "/vote/thanks");
            exit();
        } catch (Exception $e) {
            $this->setFlash('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
            header("Location: " . BASE_URL . "/vote");
            exit();
        }
    }

    public function thanks()
    {
        // Kita harus mengambil data lagi supaya bisa ditampilkan di Struk/PDF
        $pemilihModel = new Pemilih();
        $voteModel = new Vote();

        // 1. Ambil Data Pemilih
        $user = $pemilihModel->find($_SESSION['pemilih_id']);

        // 2. Ambil Rincian Pilihan (Pareses, Majelis, BPK)
        $daftar_pilihan = $voteModel->getPilihanByPemilih($_SESSION['pemilih_id']);

        // 3. Kirim ke View
        $data = [
            'pemilih' => $user,
            'pilihan' => $daftar_pilihan
        ];

        $this->view('pemilih/terima_kasih', $data);
    }

    public function logout()
    {
        session_destroy();
        header("Location: " . BASE_URL);
        exit();
    }

    protected function view($view, $data = [])
    {
        extract($data);
        if (isset($_SESSION['error'])) {
            $error = $_SESSION['error'];
            unset($_SESSION['error']);
        }

        require_once "./views/layouts/header.php";
        require_once "./views/$view.php";
        require_once "./views/layouts/footer.php";
    }

    protected function setFlash($key, $message)
    {
        $_SESSION[$key] = $message;
    }
}
