<?php

// --- PEMUATAN MODEL DENGAN JALUR ABSOLUT (ANTI-ERROR) ---
require_once __DIR__ . '/../models/Vote.php';
require_once __DIR__ . '/../models/Pemilih.php';
require_once __DIR__ . '/../models/Calon.php';
require_once __DIR__ . '/../models/Admin.php';

class AdminController
{
    // Default page: Login
    public function index()
    {
        $this->login();
    }

    // --- AUTHENTICATION ---

    public function login()
    {
        if (isset($_SESSION['admin_id'])) {
            header("Location: " . BASE_URL . "/admin/dashboard");
            exit();
        }
        $this->view('admin/login');
    }

    public function auth()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $adminModel = new Admin();
            $admin = $adminModel->login($_POST['username'], $_POST['password']);

            if ($admin) {
                $_SESSION['admin_id'] = $admin['id'];
                $_SESSION['admin_username'] = $admin['username'];
                header("Location: " . BASE_URL . "/admin/dashboard");
                exit();
            } else {
                $this->view('admin/login', ['error' => 'Username atau Password salah.']);
            }
        }
    }

    public function logout()
    {
        session_destroy();
        header("Location: " . BASE_URL . "/admin/login");
        exit();
    }

    // --- DASHBOARD ---

    public function dashboard()
    {
        $this->checkAuth();

        try {
            $voteModel = new Vote();
            $data['stats'] = $voteModel->getStats();
            $this->adminView('admin/dashboard', $data);
        } catch (Exception $e) {
            echo "Error Dashboard: " . $e->getMessage();
        }
    }

    // --- CRUD PEMILIH & QR CODE ---

    public function pemilih()
    {
        $this->checkAuth();
        $pemilihModel = new Pemilih();
        $data['pemilih'] = $pemilihModel->getAll();
        $data['pesan'] = $this->getFlash('message');
        $this->adminView('admin/crud_pemilih', $data);
    }

    public function createPemilih()
    {
        $this->checkAuth();
        $data['pemilih'] = null;
        $data['error'] = $this->getFlash('error');
        $this->adminView('admin/form_pemilih', $data);
    }

    public function storePemilih()
    {
        $this->checkAuth();
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $nama = trim($_POST['nama'] ?? '');
            $unsur = trim($_POST['unsur'] ?? '');
            $daerah_lembaga = trim($_POST['daerah_lembaga'] ?? '');
            $resort = trim($_POST['resort'] ?? '');

            // 1. Validasi Umum
            if (empty($nama) || empty($unsur) || empty($daerah_lembaga)) {
                $this->setFlash('error', 'Nama, Unsur, dan Daerah/Lembaga wajib diisi.');
                header("Location: " . BASE_URL . "/admin/createPemilih");
                exit();
            }

            // 2. Validasi Khusus (Pendeta/Jemaat Wajib Resort)
            if ($unsur === "Pendeta" || $unsur === "Anggota Jemaat") {
                if (empty($resort)) {
                    $this->setFlash('error', 'Untuk unsur Pendeta/Anggota Jemaat, kolom Resort WAJIB diisi.');
                    header("Location: " . BASE_URL . "/admin/createPemilih");
                    exit();
                }
            }

            $resortToSave = !empty($resort) ? $resort : null;

            // 3. Simpan & Redirect ke QR
            try {
                $pemilihModel = new Pemilih();
                $newId = $pemilihModel->create($nama, $unsur, $daerah_lembaga, $resortToSave);

                if ($newId) {
                    // Redirect ke halaman lihat QR
                    header("Location: " . BASE_URL . "/admin/viewQr/" . $newId);
                    exit();
                }
            } catch (Exception $e) {
                $this->setFlash('error', $e->getMessage());
                header("Location: " . BASE_URL . "/admin/createPemilih");
                exit();
            }
        }
    }

    // Tampilkan QR Code
    public function viewQr($id)
    {
        $this->checkAuth();
        $pemilihModel = new Pemilih();
        $pemilih = $pemilihModel->find($id);

        if (!$pemilih) {
            header("Location: " . BASE_URL . "/admin/pemilih");
            exit();
        }

        $data['pemilih'] = $pemilih;
        $data['loginLink'] = BASE_URL . "/auth/scan?t=" . $pemilih['token'];

        $this->adminView('admin/view_qr', $data);
    }

    public function editPemilih($id)
    {
        $this->checkAuth();
        $pemilihModel = new Pemilih();
        $data['pemilih'] = $pemilihModel->find($id);
        $data['error'] = $this->getFlash('error');
        if (!$data['pemilih']) {
            header("Location: " . BASE_URL . "/admin/pemilih");
            exit();
        }
        $this->adminView('admin/form_pemilih', $data);
    }

    public function updatePemilih($id)
    {
        $this->checkAuth();
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $nama = trim($_POST['nama'] ?? '');
            $unsur = trim($_POST['unsur'] ?? '');
            $daerah_lembaga = trim($_POST['daerah_lembaga'] ?? '');
            $resort = trim($_POST['resort'] ?? '');

            if (empty($nama) || empty($unsur) || empty($daerah_lembaga)) {
                $this->setFlash('error', 'Nama, Unsur, dan Daerah/Lembaga wajib diisi.');
                header("Location: " . BASE_URL . "/admin/editPemilih/" . $id);
                exit();
            }

            if ($unsur === "Pendeta" || $unsur === "Anggota Jemaat") {
                if (empty($resort)) {
                    $this->setFlash('error', 'Untuk unsur Pendeta/Anggota Jemaat, kolom Resort WAJIB diisi.');
                    header("Location: " . BASE_URL . "/admin/editPemilih/" . $id);
                    exit();
                }
            }

            $resortToSave = !empty($resort) ? $resort : null;

            try {
                $pemilihModel = new Pemilih();
                $pemilihModel->update($id, $nama, $unsur, $daerah_lembaga, $resortToSave);
                $this->setFlash('message', 'Data pemilih berhasil diupdate.');
                header("Location: " . BASE_URL . "/admin/pemilih");
                exit();
            } catch (Exception $e) {
                $this->setFlash('error', $e->getMessage());
                header("Location: " . BASE_URL . "/admin/editPemilih/" . $id);
                exit();
            }
        }
    }

    public function deletePemilih($id)
    {
        $this->checkAuth();
        $pemilihModel = new Pemilih();
        $pemilihModel->delete($id);
        $this->setFlash('message', 'Data pemilih berhasil dihapus.');
        header("Location: " . BASE_URL . "/admin/pemilih");
        exit();
    }

    // --- CRUD CALON (PARESES, MAJELIS, BPK) ---

    // 1. PARESES
    public function pareses()
    {
        $this->checkAuth();
        $calonModel = new Calon();
        $data['calon'] = $calonModel->getAllPareses();
        $data['pesan'] = $this->getFlash('message');
        $this->adminView('admin/crud_calon_pareses', $data);
    }
    public function createPareses()
    {
        $this->checkAuth();
        $data['calon'] = null;
        $data['error'] = $this->getFlash('error');
        $this->adminView('admin/form_calon_pareses', $data);
    }
    public function storePareses()
    {
        $this->checkAuth();
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $calonModel = new Calon();
            $calonModel->createPareses($_POST['nama'], $_POST['daerah']);
            $this->setFlash('message', 'Calon berhasil ditambahkan.');
            header("Location: " . BASE_URL . "/admin/pareses");
            exit();
        }
    }
    public function editPareses($id)
    {
        $this->checkAuth();
        $calonModel = new Calon();
        $data['calon'] = $calonModel->findPareses($id);
        $this->adminView('admin/form_calon_pareses', $data);
    }
    public function updatePareses($id)
    {
        $this->checkAuth();
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $calonModel = new Calon();
            $calonModel->updatePareses($id, $_POST['nama'], $_POST['daerah']);
            $this->setFlash('message', 'Calon berhasil diupdate.');
            header("Location: " . BASE_URL . "/admin/pareses");
            exit();
        }
    }
    public function deletePareses($id)
    {
        $this->checkAuth();
        $calonModel = new Calon();
        $calonModel->deletePareses($id);
        $this->setFlash('message', 'Calon berhasil dihapus.');
        header("Location: " . BASE_URL . "/admin/pareses");
        exit();
    }

    // 2. MAJELIS
    public function majelis()
    {
        $this->checkAuth();
        $calonModel = new Calon();
        $data['calon'] = $calonModel->getAllMajelisPusat();
        $data['pesan'] = $this->getFlash('message');
        $this->adminView('admin/crud_calon_majelis', $data);
    }
    public function createMajelis()
    {
        $this->checkAuth();
        $data['calon'] = null;
        $this->adminView('admin/form_calon_majelis', $data);
    }
    public function storeMajelis()
    {
        $this->checkAuth();
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $calonModel = new Calon();
            $calonModel->createMajelisPusat($_POST['nama'], $_POST['keterangan']);
            $this->setFlash('message', 'Calon berhasil ditambahkan.');
            header("Location: " . BASE_URL . "/admin/majelis");
            exit();
        }
    }
    public function editMajelis($id)
    {
        $this->checkAuth();
        $calonModel = new Calon();
        $data['calon'] = $calonModel->findMajelisPusat($id);
        $this->adminView('admin/form_calon_majelis', $data);
    }
    public function updateMajelis($id)
    {
        $this->checkAuth();
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $calonModel = new Calon();
            $calonModel->updateMajelisPusat($id, $_POST['nama'], $_POST['keterangan']);
            $this->setFlash('message', 'Calon berhasil diupdate.');
            header("Location: " . BASE_URL . "/admin/majelis");
            exit();
        }
    }
    public function deleteMajelis($id)
    {
        $this->checkAuth();
        $calonModel = new Calon();
        $calonModel->deleteMajelisPusat($id);
        $this->setFlash('message', 'Calon berhasil dihapus.');
        header("Location: " . BASE_URL . "/admin/majelis");
        exit();
    }

    // 3. BPK
    public function bpk()
    {
        $this->checkAuth();
        $calonModel = new Calon();
        $data['calon'] = $calonModel->getAllBPK();
        $data['pesan'] = $this->getFlash('message');
        $this->adminView('admin/crud_calon_bpk', $data);
    }
    public function createBPK()
    {
        $this->checkAuth();
        $data['calon'] = null;
        $this->adminView('admin/form_calon_bpk', $data);
    }
    public function storeBPK()
    {
        $this->checkAuth();
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $calonModel = new Calon();
            $calonModel->createBPK($_POST['nama'], $_POST['keterangan']);
            $this->setFlash('message', 'Calon berhasil ditambahkan.');
            header("Location: " . BASE_URL . "/admin/bpk");
            exit();
        }
    }
    public function editBPK($id)
    {
        $this->checkAuth();
        $calonModel = new Calon();
        $data['calon'] = $calonModel->findBPK($id);
        $this->adminView('admin/form_calon_bpk', $data);
    }
    public function updateBPK($id)
    {
        $this->checkAuth();
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $calonModel = new Calon();
            $calonModel->updateBPK($id, $_POST['nama'], $_POST['keterangan']);
            $this->setFlash('message', 'Calon berhasil diupdate.');
            header("Location: " . BASE_URL . "/admin/bpk");
            exit();
        }
    }
    public function deleteBPK($id)
    {
        $this->checkAuth();
        $calonModel = new Calon();
        $calonModel->deleteBPK($id);
        $this->setFlash('message', 'Calon berhasil dihapus.');
        header("Location: " . BASE_URL . "/admin/bpk");
        exit();
    }


    // --- HELPER FUNCTIONS ---

    protected function checkAuth()
    {
        if (!isset($_SESSION['admin_id'])) {
            header("Location: " . BASE_URL . "/admin/login");
            exit();
        }
    }

    protected function view($view, $data = [])
    {
        extract($data);
        require_once "./views/layouts/header.php";
        require_once "./views/$view.php";
        require_once "./views/layouts/footer.php";
    }

    protected function adminView($view, $data = [])
    {
        extract($data);
        require_once "./views/layouts/admin_header.php";
        require_once "./views/$view.php";
        require_once "./views/layouts/admin_footer.php";
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
