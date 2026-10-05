<?php
// --- 1. PERSIAPAN DATA ---
if (!isset($stats) || !is_array($stats)) {
    $stats = ['pareses' => [], 'majelis' => [], 'bpk' => []];
}

/**
 * Urutkan kandidat dan hitung peringkat, persentase, selisih.
 * Tiap kategori hanya satu pemenang, jadi seri di peringkat 1 ditandai
 * sebagai "perlu keputusan lanjutan".
 */
function buildRanking($candidates)
{
    $items = array_values(is_array($candidates) ? $candidates : []);

    usort($items, function ($a, $b) {
        if ($a['jumlah_suara'] == $b['jumlah_suara']) {
            return strcasecmp($a['nama'], $b['nama']);
        }
        return $b['jumlah_suara'] <=> $a['jumlah_suara'];
    });

    $total = 0;
    foreach ($items as $c) $total += (int) $c['jumlah_suara'];
    $max = !empty($items) ? (int) $items[0]['jumlah_suara'] : 0;

    $rank = 0;
    $prev = null;
    $winners = [];
    $secondScore = null;
    foreach ($items as $i => &$c) {
        $s = (int) $c['jumlah_suara'];
        if ($s !== $prev) $rank = $i + 1;
        $prev = $s;
        $c['rank']      = $rank;
        $c['persen']    = $total > 0 ? ($s / $total) * 100 : 0;
        $c['selisih']   = $max - $s;
        $c['is_winner'] = ($rank === 1 && $max > 0);
        if ($c['is_winner']) $winners[] = $c['nama'];
        if ($rank > 1 && $secondScore === null) $secondScore = $s;
    }
    unset($c);

    return [
        'items'   => $items,
        'total'   => $total,
        'max'     => $max,
        'winners' => $winners,
        'is_tie'  => count($winners) > 1,
        'margin'  => ($secondScore !== null && count($winners) === 1) ? $max - $secondScore : null,
    ];
}

function fmtPersen($p)
{
    return number_format($p, 1, ',', '.') . '%';
}

/** Kartu pemenang */
function renderWinnerCard($r)
{
    echo '<div class="winner-card">';
    echo '<div class="winner-label">' . ($r['is_tie'] ? 'Seri di peringkat 1' : 'Pemenang') . '</div>';

    if ($r['max'] == 0) {
        echo '<div class="winner-name muted">Belum ada suara masuk</div>';
    } else {
        echo '<div class="winner-name">' . htmlspecialchars(implode(' & ', $r['winners'])) . '</div>';
        echo '<div class="winner-meta">';
        echo '<div>Jumlah suara : ' . $r['max'] . ' suara</div>';
        echo '<div>Total Persentase : ' . fmtPersen($r['items'][0]['persen']) . '</div>';
        if ($r['margin'] !== null) {
            echo '<div class="winner-margin">Unggul ' . $r['margin'] . ' suara dari peringkat 2</div>';
        }
        echo '</div>';
        if ($r['is_tie']) {
            echo '<div class="tie-note">Hanya satu yang dapat terpilih, sehingga perlu keputusan lanjutan.</div>';
        }
    }
    echo '</div>';
}

/** Satu bagian kategori */
function renderCategory($title, $icon, $accent, $canvasId, $r, $showDaerah = false)
{
?>
    <section class="cat-section" style="--accent: <?php echo $accent; ?>;">
        <div class="cat-head">
            <div class="cat-title"><i class="bi <?php echo $icon; ?>"></i> <?php echo htmlspecialchars($title); ?></div>
            <div class="cat-meta"><?php echo count($r['items']); ?> calon &middot; <?php echo $r['total']; ?> suara</div>
        </div>

        <?php renderWinnerCard($r); ?>

        <div class="row g-4">
            <div class="col-lg-5">
                <div class="block-label">Peringkat</div>
                <div class="scroll-area">
                    <?php foreach ($r['items'] as $c):
                        $barPct = $r['max'] > 0 ? ($c['jumlah_suara'] / $r['max']) * 100 : 0;
                        $tip = $c['is_winner'] ? '' : '-' . $c['selisih'] . ' suara dari peringkat 1';
                    ?>
                        <div class="cand-row <?php echo $c['is_winner'] ? 'is-winner' : ''; ?>" title="<?php echo htmlspecialchars($tip); ?>">
                            <div class="cand-rank"><?php echo $r['max'] > 0 ? $c['rank'] : '–'; ?></div>
                            <div class="cand-main">
                                <div class="cand-name"><?php echo htmlspecialchars($c['nama']); ?></div>
                                <?php if ($showDaerah && !empty($c['daerah'])): ?>
                                    <div class="cand-sub"><?php echo htmlspecialchars($c['daerah']); ?></div>
                                <?php endif; ?>
                                <div class="cand-bar"><span style="width: <?php echo round($barPct, 1); ?>%;"></span></div>
                            </div>
                            <div class="cand-votes">
                                <strong><?php echo (int) $c['jumlah_suara']; ?></strong>
                                <small><?php echo fmtPersen($c['persen']); ?></small>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="col-lg-7">
                <div class="block-label">Grafik</div>
                <div class="chart-scroll">
                    <div class="chart-inner" style="height: 400px; min-width: <?php echo count($r['items']) * 46; ?>px;">
                        <canvas id="<?php echo $canvasId; ?>"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </section>
<?php
}

// --- 2. HITUNG RANKING ---
$rankPareses = buildRanking($stats['pareses'] ?? []);
$rankMajelis = buildRanking($stats['majelis'] ?? []);
$rankBpk     = buildRanking($stats['bpk'] ?? []);

// --- 3. DATA CHART ---
$jsonFlags = JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT;
function chartPayload($r, $color)
{
    return [
        'labels' => array_column($r['items'], 'nama'),
        'data'   => array_map('intval', array_column($r['items'], 'jumlah_suara')),
        'winner' => array_column($r['items'], 'is_winner'),
        'color'  => $color,
    ];
}
$chartData = [
    'chartPareses' => chartPayload($rankPareses, '#198754'),
    'chartMajelis' => chartPayload($rankMajelis, '#e8a100'),
    'chartBpk'     => chartPayload($rankBpk, '#0d6efd'),
];
?>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4"></script>

<style>
    body {
        background-color: #f6f7f9;
    }

    .dash-wrap {
        max-width: 1100px;
        margin: 0 auto;
    }

    /* Section */
    .cat-section {
        background: #fff;
        border-radius: 16px;
        padding: 2rem;
        margin-bottom: 2.5rem;
        box-shadow: 0 1px 3px rgba(0, 0, 0, .05);
        border-top: 4px solid var(--accent);
    }

    .cat-head {
        display: flex;
        justify-content: space-between;
        align-items: baseline;
        flex-wrap: wrap;
        gap: .5rem;
        margin-bottom: 1.5rem;
    }

    .cat-title {
        font-size: 1.25rem;
        font-weight: 700;
        color: #212529;
    }

    .cat-title i {
        color: var(--accent);
        margin-right: .25rem;
    }

    .cat-meta {
        font-size: .9rem;
        color: #6c757d;
    }

    /* Winner */
    .winner-card {
        background: #fafafa;
        border-left: 4px solid var(--accent);
        border-radius: 10px;
        padding: 1.25rem 1.5rem;
        margin-bottom: 2rem;
    }

    .winner-label {
        font-size: .75rem;
        letter-spacing: .08em;
        text-transform: uppercase;
        color: #6c757d;
        font-weight: 600;
    }

    .winner-name {
        font-size: 1.6rem;
        font-weight: 800;
        color: #212529;
        margin: .25rem 0;
    }

    .winner-name.muted {
        color: #adb5bd;
        font-weight: 600;
        font-size: 1.2rem;
    }

    .winner-meta {
        color: #495057;
        font-size: .95rem;
    }

    .winner-margin {
        margin-top: .35rem;
        font-size: .85rem;
        color: #868e96;
    }

    .tie-note {
        margin-top: .6rem;
        font-size: .85rem;
        color: #b02a37;
    }

    /* Block label */
    .block-label {
        font-size: .75rem;
        letter-spacing: .08em;
        text-transform: uppercase;
        color: #6c757d;
        font-weight: 600;
        margin-bottom: .75rem;
    }

    /* List */
    .scroll-area {
        max-height: 460px;
        overflow-y: auto;
        padding-right: 8px;
    }

    .scroll-area::-webkit-scrollbar {
        width: 6px;
    }

    .scroll-area::-webkit-scrollbar-thumb {
        background: #d5d8dc;
        border-radius: 10px;
    }

    .cand-row {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: .9rem .25rem;
        border-bottom: 1px solid #eef0f2;
    }

    .cand-row:last-child {
        border-bottom: none;
    }

    .cand-rank {
        width: 28px;
        text-align: center;
        font-weight: 700;
        color: #adb5bd;
    }

    .cand-row.is-winner .cand-rank {
        color: var(--accent);
        font-size: 1.1rem;
    }

    .cand-main {
        flex: 1;
        min-width: 0;
    }

    .cand-name {
        font-weight: 600;
        color: #212529;
        line-height: 1.3;
    }

    .cand-row.is-winner .cand-name {
        font-weight: 700;
    }

    .cand-sub {
        font-size: .8rem;
        color: #868e96;
    }

    .cand-bar {
        height: 4px;
        background: #eef0f2;
        border-radius: 4px;
        margin-top: .5rem;
        overflow: hidden;
    }

    .cand-bar span {
        display: block;
        height: 100%;
        background: var(--accent);
        opacity: .35;
        border-radius: 4px;
    }

    .cand-row.is-winner .cand-bar span {
        opacity: 1;
    }

    .cand-votes {
        text-align: right;
        min-width: 56px;
        line-height: 1.2;
    }

    .cand-votes strong {
        display: block;
        font-size: 1.15rem;
        color: #212529;
    }

    .cand-votes small {
        color: #868e96;
        font-size: .78rem;
    }

    /* Chart */
    .chart-scroll {
        overflow-x: auto;
        overflow-y: hidden;
    }

    .chart-inner {
        position: relative;
        width: 100%;
    }

    @media print {

        .scroll-area,
        .chart-scroll {
            max-height: none !important;
            overflow: visible !important;
        }

        .cat-section {
            break-inside: avoid;
            box-shadow: none;
            border: 1px solid #ddd;
        }

        .btn {
            display: none !important;
        }
    }
</style>

<div class="dash-wrap">
    <div class="d-flex justify-content-between align-items-center mb-4 mt-3">
        <div>
            <h2 class="fw-bold text-dark mb-1">Dashboard Statistik</h2>
            <p class="text-muted mb-0">Dimuat pada <?php echo date('d/m/Y H:i'); ?></p>
        </div>
        <div>
            <button onclick="window.location.reload()" class="btn btn-light border me-2"><i class="bi bi-arrow-clockwise"></i> Refresh</button>
            <button onclick="window.print()" class="btn btn-dark"><i class="bi bi-printer"></i> Cetak</button>
        </div>
    </div>

    <?php
    renderCategory('Pareses',       'bi-people-fill',     '#198754', 'chartPareses', $rankPareses, true);
    renderCategory('Majelis Pusat', 'bi-building-fill',   '#e8a100', 'chartMajelis', $rankMajelis);
    renderCategory('BPK',           'bi-calculator-fill', '#0d6efd', 'chartBpk',     $rankBpk);
    ?>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const charts = <?php echo json_encode($chartData, $jsonFlags); ?>;

        Object.keys(charts).forEach(function(id) {
            const c = charts[id];
            new Chart(document.getElementById(id), {
                type: 'bar',
                data: {
                    labels: c.labels,
                    datasets: [{
                        data: c.data,
                        // Pemenang berwarna penuh, lainnya lebih pudar
                        backgroundColor: c.winner.map(w => w ? c.color : c.color + '55'),
                        borderRadius: 4,
                        maxBarThickness: 36
                    }]
                },
                options: {
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
                                precision: 0
                            },
                            grid: {
                                color: '#f1f3f5'
                            },
                            border: {
                                display: false
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
                            border: {
                                display: false
                            },
                            ticks: {
                                font: {
                                    size: 11
                                },
                                maxRotation: 45,
                                minRotation: 45
                            }
                        }
                    },
                    animation: {
                        duration: 600
                    }
                }
            });
        });
    });
</script>