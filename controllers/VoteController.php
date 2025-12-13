<?php

class VoteController
{
    public function __construct()
    {
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

        // --- PERBAIKAN 1: BACA DATA LAMA (RE-POPULATE) ---
        // Jangan di-unset! Ambil data dari sesi jika ada.
        $data['selected'] = $_SESSION['temp_vote'] ?? [
            'pareses' => [],
            'majelis' => [],
            'bpk' => []
        ];
        
        $this->view('pemilih/pemilihan', $data);
    }

    public function submit()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            
            // Ambil data form
            $pareses_ids = $_POST['pareses'] ?? [];
            $majelis_ids = $_POST['majelis'] ?? [];
            $bpk_ids = $_POST['bpk'] ?? [];

            // --- PERBAIKAN 2: SIMPAN KE SESI DULUAN ---
            // Simpan data input ke session SEBELUM validasi.
            // Supaya jika validasi gagal, centangan user tidak hilang saat kembali ke index.
            $_SESSION['temp_vote'] = [
                'pareses' => $pareses_ids, 
                'majelis' => $majelis_ids, 
                'bpk' => $bpk_ids          
            ];

            // --- BARU LAKUKAN VALIDASI ---
            
            // 1. Validasi Pareses (Min 1, Max 16)
            if (count($pareses_ids) < 1 || count($pareses_ids) > 16) {
                $this->setFlash('error', 'Pareses: Pilih minimal 1, maksimal 16 calon.');
                header("Location: " . BASE_URL . "/vote");
                return;
            }
            
            // 2. Validasi Majelis (TEPAT 15)
            if (count($majelis_ids) !== 15) {
                $this->setFlash('error', 'Majelis Pusat: Anda harus memilih TEPAT 15 calon.');
                header("Location: " . BASE_URL . "/vote");
                return;
            }
            
            // 3. Validasi BPK (TEPAT 3)
            if (count($bpk_ids) !== 3) {
                $this->setFlash('error', 'Badan Pemeriksa Keuangan: Anda harus memilih TEPAT 3 calon.');
                header("Location: " . BASE_URL . "/vote");
                return;
            }

            // Jika lolos validasi, lanjut ke konfirmasi
            header("Location: " . BASE_URL . "/vote/confirm");
            exit();
        }
    }

    // --- SISA FUNGSI DI BAWAH INI TETAP SAMA ---

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
            $voteModel->saveVote(
                $pemilih_id,
                $voteData['pareses'], 
                $voteData['majelis'], 
                $voteData['bpk']
            );

            // Hapus sesi HANYA jika sudah sukses disimpan ke DB
            unset($_SESSION['temp_vote']);

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
        $this->view('pemilih/terima_kasih');
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
        $data['error'] = $this->getFlash('error'); 
        require_once "./views/layouts/header.php";
        require_once "./views/$view.php";
        require_once "./views/layouts/footer.php";
    }

    protected function setFlash($key, $message)
    {
        $_SESSION[$key] = $message;
    }

    protected function getFlash($key)
    {
        if (isset($_SESSION[$key])) {
            $message = $_SESSION[$key];
            unset($_SESSION[$key]);
            return $message;
        }
        return null;
    }
}