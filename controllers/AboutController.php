<?php

class AboutController
{
    // Method default saat akses /about
    public function index()
    {
        // Kita bisa kirim data judul jika mau
        $data['page_title'] = 'Tentang E-Voting';

        // Panggil view 'about/index'
        $this->view('about/index', $data);
    }

    public function guide()
    {
        $data['page_title'] = 'Tata Cara Pemilihan';

        $this->view('about/guide', $data);
    }
    // Fungsi Helper View (Sama seperti di controller lain)
    protected function view($view, $data = [])
    {
        extract($data);
        require_once "./views/layouts/header.php";
        require_once "./views/$view.php";
        require_once "./views/layouts/footer.php";
    }
}
