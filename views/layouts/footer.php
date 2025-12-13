</div>
<footer class="py-2 mt-auto bg-light" id="mainFooter" style="background-color: #f8f9fa !important; border-top: 4px solid #198754;">
    <div class="container text-center text-secondary">
        <p class="mb-1">
            &copy; 2025 <strong class="text-success fw-bold">E-Voting System</strong>. All rights reserved.
        </p>
        <small style="font-size: 0.85rem; opacity: 0.7;">
            Panitia Pemilihan &copy; Periode 2025-2030
        </small>
    </div>
</footer>

<div class="modal fade" id="aboutModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content rounded-4 border-0 shadow-lg">

            <div class="modal-header bg-success text-white border-0">
                <h5 class="modal-title fw-bold">
                    <i class="bi bi-info-circle-fill me-2"></i> Pusat Informasi
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body p-0">

                <ul class="nav nav-tabs nav-fill" id="aboutTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active fw-bold py-3 text-success" id="info-tab" data-bs-toggle="tab" data-bs-target="#info-pane" type="button">
                            Tentang Sistem
                        </button>
                    </li>

                </ul>

                <div class="tab-content p-4">

                    <div class="tab-pane fade show active" id="info-pane">

                        <div class="text-center mb-4">
                            <div class="d-inline-block p-2 bg-light rounded-circle shadow-sm mb-2">
                                <img src="<?php echo BASE_URL; ?>/assets/img/download.png" alt="Logo" width="50">
                            </div>
                            <h5 class="fw-bold text-dark">Digital Election Platform</h5>
                            <p class="text-muted small">Solusi pemilihan modern, transparan, dan akuntabel.</p>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <ul class="list-group list-group-flush small">
                                    <li class="list-group-item border-0 ps-0">
                                        <i class="bi bi-check-circle-fill text-success me-2"></i>
                                        <strong>One-Time Token:</strong> Kode QR hanya berlaku 1x pakai.
                                    </li>
                                    <li class="list-group-item border-0 ps-0">
                                        <i class="bi bi-check-circle-fill text-success me-2"></i>
                                        <strong>Enkripsi Data:</strong> Pilihan suara disimpan secara anonim.
                                    </li>
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <ul class="list-group list-group-flush small">
                                    <li class="list-group-item border-0 ps-0">
                                        <i class="bi bi-check-circle-fill text-success me-2"></i>
                                        <strong>Paperless:</strong> Ramah lingkungan tanpa kertas suara.
                                    </li>
                                    <li class="list-group-item border-0 ps-0">
                                        <i class="bi bi-check-circle-fill text-success me-2"></i>
                                        <strong>Real-Time:</strong> Hasil dihitung otomatis oleh server.
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <h6 class="fw-bold text-dark border-bottom pb-2 mb-3"><i class="bi bi-question-circle-fill me-2 text-warning"></i>Pertanyaan Umum (FAQ)</h6>

                        <div class="accordion accordion-flush" id="faqAccordion">

                            <div class="accordion-item border rounded mb-2">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed bg-light py-2 small fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">
                                        Apakah pilihan saya rahasia?
                                    </button>
                                </h2>
                                <div id="faq1" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                    <div class="accordion-body small text-muted py-2">
                                        <strong>Sangat Rahasia.</strong> Sistem memisahkan data identitas pemilih dengan data suara yang masuk. Admin tidak bisa melihat siapa memilih siapa.
                                    </div>
                                </div>
                            </div>

                            <div class="accordion-item border rounded mb-2">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed bg-light py-2 small fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">
                                        Bisakah saya memilih ulang jika salah?
                                    </button>
                                </h2>
                                <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                    <div class="accordion-body small text-muted py-2">
                                        <strong>Tidak Bisa.</strong> Tetapi sistem akan memberikan halaman konfirmasi untuk memastikan pilihan anda, jadi anda diberi kesempatan untuk memastikan pilihan anda,Terimah kasih!.
                                    </div>
                                </div>
                            </div>

                            <div class="accordion-item border rounded">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed bg-light py-2 small fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#faq3">
                                        Apa yang terjadi jika HP mati saat memilih?
                                    </button>
                                </h2>
                                <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                    <div class="accordion-body small text-muted py-2">
                                        Selama Anda <strong>belum menekan tombol Kirim</strong>, Anda bisa memindai (scan) ulang QR Code Anda menggunakan HP lain dan melanjutkan proses pemilihan.
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="tab-pane fade" id="guide-pane">
                        <div class="d-flex flex-column gap-3">


                        </div>
                    </div>

                    <div class="modal-footer border-0 bg-light justify-content-center py-3">
                        <button type="button" class="btn btn-secondary rounded-pill px-5 btn-sm" data-bs-dismiss="modal">Tutup</button>
                    </div>
                </div>
            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

        <script src="<?php echo BASE_URL; ?>/assets/js/main.js"></script>

        </body>

        </html>