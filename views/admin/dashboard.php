<?php
// --- 1. PERSIAPAN DATA PHP ---
if (!isset($stats) || !is_array($stats)) {
    $stats = ['pareses' => [], 'majelis' => [], 'bpk' => []];
}

// --- 2. DATA UNTUK CHART (JSON) ---
$jsParesesLabels = json_encode(array_column($stats['pareses'], 'nama'));
$jsParesesData   = json_encode(array_column($stats['pareses'], 'jumlah_suara'));
$jsMajelisLabels = json_encode(array_column($stats['majelis'], 'nama'));
$jsMajelisData   = json_encode(array_column($stats['majelis'], 'jumlah_suara'));
$jsBpkLabels     = json_encode(array_column($stats['bpk'], 'nama'));
$jsBpkData       = json_encode(array_column($stats['bpk'], 'jumlah_suara'));

// --- 3. HITUNG SUARA TERTINGGI ---
$maxPareses = !empty($stats['pareses']) ? max(array_column($stats['pareses'], 'jumlah_suara')) : 0;
$maxMajelis = !empty($stats['majelis']) ? max(array_column($stats['majelis'], 'jumlah_suara')) : 0;
$maxBpk     = !empty($stats['bpk']) ? max(array_column($stats['bpk'], 'jumlah_suara')) : 0;

// --- 4. HELPER LIST PEMENANG ---
function getTopNames($candidates, $maxScore)
{
    if ($maxScore == 0 || empty($candidates)) return '-';
    $winners = [];
    foreach ($candidates as $c) {
        if ($c['jumlah_suara'] == $maxScore) $winners[] = $c['nama'];
    }
    return implode(", ", $winners);
}

$topParesesName = getTopNames($stats['pareses'], $maxPareses);
$topMajelisName = getTopNames($stats['majelis'], $maxMajelis);
$topBpkName     = getTopNames($stats['bpk'], $maxBpk);

// --- 5. HELPER CSS CLASS ---
function getWinnerClass($count, $max)
{
    if ($count == $max && $max > 0) {
        return 'border-warning bg-warning bg-opacity-10 shadow-sm';
    }
    return 'border-light bg-white';
}
?>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>
    :root {
        --soft-bg: #f8f9fa;
    }

    body {
        background-color: var(--soft-bg);
    }

    /* Style Kartu Layout */
    .card-section {
        border: none;
        border-radius: 16px;
        background: #fff;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.03);
        margin-bottom: 40px;
        overflow: hidden;
    }

    .section-header {
        padding: 1.5rem;
        color: white;
        font-weight: bold;
        font-size: 1.2rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .bg-pareses {
        background: linear-gradient(135deg, #198754, #20c997);
    }

    .bg-majelis {
        background: linear-gradient(135deg, #ffc107, #fd7e14);
        color: #333 !important;
    }

    .bg-bpk {
        background: linear-gradient(135deg, #0dcaf0, #0d6efd);
    }

    /* Scroll Area untuk Daftar Nama */
    .scroll-area {
        height: 400px;
        /* Samakan tinggi dengan grafik */
        overflow-y: auto;
        padding-right: 10px;
    }

    /* Scrollbar Cantik */
    .scroll-area::-webkit-scrollbar {
        width: 6px;
    }

    .scroll-area::-webkit-scrollbar-track {
        background: #f1f1f1;
    }

    .scroll-area::-webkit-scrollbar-thumb {
        background: #ccc;
        border-radius: 10px;
    }

    /* Kartu Calon Kecil (List Item) */
    .list-candidate-item {
        border: 1px solid #eee;
        border-radius: 10px;
        padding: 15px;
        margin-bottom: 10px;
        transition: transform 0.2s;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .list-candidate-item:hover {
        transform: translateX(5px);
    }

    /* Chart Container */
    .chart-wrapper {
        height: 400px;
        width: 100%;
        padding: 10px;
        background: #fff;
        border-radius: 12px;
    }

    /* Summary Top Cards */
    .card-summary {
        border: none;
        border-radius: 12px;
        min-height: 140px;
    }

    .winner-name {
        font-size: 1.2rem;
        font-weight: 800;
        margin-bottom: 5px;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .icon-bg {
        position: absolute;
        right: 10px;
        bottom: -10px;
        font-size: 5rem;
        opacity: 0.15;
        transform: rotate(-15deg);
    }
</style>

<div class="d-flex justify-content-between align-items-center mb-5 mt-2">
    <div>
        <h2 class="fw-bold text-dark mb-1">Dashboard Statistik</h2>
        <p class="text-muted mb-0">Pantauan perolehan suara real-time.</p>
    </div>
    <div>
        <button onclick="window.location.reload()" class="btn btn-light border shadow-sm me-2"><i class="bi bi-arrow-clockwise"></i> Refresh</button>
        <button onclick="window.print()" class="btn btn-primary shadow-sm"><i class="bi bi-printer"></i> Cetak</button>
    </div>
</div>




<div class="card card-section">
    <div class="section-header bg-pareses">
        <div><i class="bi bi-people-fill me-2"></i> Hasil Suara Pareses</div>
        <span class="badge bg-white text-success rounded-pill">Total Calon: <?php echo count($stats['pareses']); ?></span>
    </div>
    <div class="card-body p-4">
        <div class="row">
            <div class="col-lg-4 mb-4 mb-lg-0">
                <h6 class="text-muted fw-bold mb-3 border-bottom pb-2">PERINGKAT & PEROLEHAN SUARA</h6>
                <div class="scroll-area">
                    <?php foreach ($stats['pareses'] as $data): ?>
                        <div class="list-candidate-item <?php echo getWinnerClass($data['jumlah_suara'], $maxPareses); ?>">
                            <div>
                                <div class="fw-bold text-dark"><?php echo htmlspecialchars($data['nama']); ?></div>
                                <small class="text-muted"><?php echo htmlspecialchars($data['daerah']); ?></small>
                            </div>
                            <span class="badge bg-success rounded-pill fs-6"><?php echo $data['jumlah_suara']; ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="col-lg-8">
                <h6 class="text-muted fw-bold mb-3 border-bottom pb-2">VISUALISASI GRAFIK</h6>
                <div class="chart-wrapper">
                    <canvas id="chartPareses"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card card-section">
    <div class="section-header bg-majelis">
        <div><i class="bi bi-building-fill me-2"></i> Hasil Suara Majelis Pusat</div>
        <span class="badge bg-white text-warning rounded-pill">Total Calon: <?php echo count($stats['majelis']); ?></span>
    </div>
    <div class="card-body p-4">
        <div class="row">
            <div class="col-lg-4 mb-4 mb-lg-0">
                <h6 class="text-muted fw-bold mb-3 border-bottom pb-2">PERINGKAT & PEROLEHAN SUARA</h6>
                <div class="scroll-area">
                    <?php foreach ($stats['majelis'] as $data): ?>
                        <div class="list-candidate-item <?php echo getWinnerClass($data['jumlah_suara'], $maxMajelis); ?>">
                            <div>
                                <div class="fw-bold text-dark"><?php echo htmlspecialchars($data['nama']); ?></div>
                            </div>
                            <span class="badge bg-warning text-dark rounded-pill fs-6"><?php echo $data['jumlah_suara']; ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="col-lg-8">
                <h6 class="text-muted fw-bold mb-3 border-bottom pb-2">VISUALISASI GRAFIK</h6>
                <div class="chart-wrapper">
                    <canvas id="chartMajelis"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card card-section">
    <div class="section-header bg-bpk">
        <div><i class="bi bi-calculator-fill me-2"></i> Hasil Suara BPK</div>
        <span class="badge bg-white text-info rounded-pill">Total Calon: <?php echo count($stats['bpk']); ?></span>
    </div>
    <div class="card-body p-4">
        <div class="row">
            <div class="col-lg-4 mb-4 mb-lg-0">
                <h6 class="text-muted fw-bold mb-3 border-bottom pb-2">PERINGKAT & PEROLEHAN SUARA</h6>
                <div class="scroll-area">
                    <?php foreach ($stats['bpk'] as $data): ?>
                        <div class="list-candidate-item <?php echo getWinnerClass($data['jumlah_suara'], $maxBpk); ?>">
                            <div>
                                <div class="fw-bold text-dark"><?php echo htmlspecialchars($data['nama']); ?></div>
                            </div>
                            <span class="badge bg-info text-white rounded-pill fs-6"><?php echo $data['jumlah_suara']; ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="col-lg-8">
                <h6 class="text-muted fw-bold mb-3 border-bottom pb-2">VISUALISASI GRAFIK</h6>
                <div class="chart-wrapper">
                    <canvas id="chartBpk"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>


<script>
    document.addEventListener("DOMContentLoaded", function() {

        // Konfigurasi Grafik (Vertical Bar)
        const commonOptions = {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    },
                    grid: {
                        borderDash: [2, 4]
                    },
                    title: {
                        display: true,
                        text: 'Jumlah Suara'
                    }
                },
                x: {
                    grid: {
                        display: false
                    },
                    ticks: {
                        font: {
                            size: 10
                        },
                        maxRotation: 45, // Nama miring agar muat
                        minRotation: 45
                    }
                }
            },
            animation: {
                duration: 1000
            }
        };

        // 1. PARESES
        new Chart(document.getElementById('chartPareses'), {
            type: 'bar',
            data: {
                labels: <?php echo $jsParesesLabels; ?>,
                datasets: [{
                    data: <?php echo $jsParesesData; ?>,
                    backgroundColor: '#198754',
                    borderRadius: 4
                }]
            },
            options: commonOptions
        });

        // 2. MAJELIS
        new Chart(document.getElementById('chartMajelis'), {
            type: 'bar',
            data: {
                labels: <?php echo $jsMajelisLabels; ?>,
                datasets: [{
                    data: <?php echo $jsMajelisData; ?>,
                    backgroundColor: '#ffc107',
                    borderRadius: 4
                }]
            },
            options: commonOptions
        });

        // 3. BPK
        new Chart(document.getElementById('chartBpk'), {
            type: 'bar',
            data: {
                labels: <?php echo $jsBpkLabels; ?>,
                datasets: [{
                    data: <?php echo $jsBpkData; ?>,
                    backgroundColor: '#0dcaf0',
                    borderRadius: 4
                }]
            },
            options: commonOptions
        });
    });
</script>