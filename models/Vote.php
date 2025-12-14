<?php
// Pastikan class Model (parent) sudah dimuat di index.php

class Vote extends Model
{
    /**
     * Menyimpan Suara dengan Proteksi Anti-Double Vote
     */
    public function saveVote($pemilih_id, $pareses_ids, $majelis_ids, $bpk_ids)
    {
        try {
            // 1. Mulai Transaksi Database
            $this->db->beginTransaction();

            // Kunci Pemilih
            $stmtLock = $this->db->prepare("UPDATE pemilih SET status_vote = 1, token_used = 1 WHERE id = ? AND status_vote = 0");
            $stmtLock->execute([$pemilih_id]);

            if ($stmtLock->rowCount() == 0) {
                throw new Exception("DOUBLE VOTE DETECTED: Suara Anda sudah terekam dari perangkat lain.");
            }

            // 2. Simpan Pareses
            $stmtPareses = $this->db->prepare("INSERT INTO vote_pareses (pemilih_id, calon_pareses_id) VALUES (?, ?)");
            foreach ($pareses_ids as $p_id) {
                $stmtPareses->execute([$pemilih_id, $p_id]);
            }

            // 3. Simpan Majelis (Perhatikan nama tabel: vote_majelis_pusat)
            $stmtMajelis = $this->db->prepare("INSERT INTO vote_majelis_pusat (pemilih_id, calon_majelis_pusat_id) VALUES (?, ?)");
            foreach ($majelis_ids as $m_id) {
                $stmtMajelis->execute([$pemilih_id, $m_id]);
            }

            // 4. Simpan BPK
            $stmtBPK = $this->db->prepare("INSERT INTO vote_bpk (pemilih_id, calon_bpk_id) VALUES (?, ?)");
            foreach ($bpk_ids as $b_id) {
                $stmtBPK->execute([$pemilih_id, $b_id]);
            }

            // 5. Commit
            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }

    /**
     * Mengambil Statistik Lengkap untuk Dashboard Admin
     */
    public function getStats()
    {
        $stats = [];
        $selectBreakdown = "
            COUNT(v.id) as jumlah_suara,
            SUM(IF(p.unsur = 'Pendeta', 1, 0)) as suara_pendeta,
            SUM(IF(p.unsur = 'Anggota Jemaat', 1, 0)) as suara_jemaat,
            SUM(IF(p.unsur IN ('Guru Jemaat', 'Penatua'), 1, 0)) as suara_sintua,
            SUM(IF(p.unsur LIKE 'Lembaga%' OR p.unsur LIKE 'Ketua%' OR p.unsur LIKE 'UNSUR%', 1, 0)) as suara_lembaga
        ";

        // 1. PARESES
        $stats['pareses'] = $this->db->query("
            SELECT c.nama, c.daerah, $selectBreakdown
            FROM calon_pareses c
            LEFT JOIN vote_pareses v ON c.id = v.calon_pareses_id
            LEFT JOIN pemilih p ON v.pemilih_id = p.id
            GROUP BY c.id, c.nama, c.daerah
            ORDER BY jumlah_suara DESC
        ")->fetchAll(PDO::FETCH_ASSOC);

        // 2. MAJELIS
        $stats['majelis'] = $this->db->query("
            SELECT c.nama, c.keterangan, $selectBreakdown
            FROM calon_majelis_pusat c
            LEFT JOIN vote_majelis_pusat v ON c.id = v.calon_majelis_pusat_id
            LEFT JOIN pemilih p ON v.pemilih_id = p.id
            GROUP BY c.id, c.nama, c.keterangan
            ORDER BY jumlah_suara DESC
        ")->fetchAll(PDO::FETCH_ASSOC);

        // 3. BPK
        $stats['bpk'] = $this->db->query("
            SELECT c.nama, c.keterangan, $selectBreakdown
            FROM calon_bpk c
            LEFT JOIN vote_bpk v ON c.id = v.calon_bpk_id
            LEFT JOIN pemilih p ON v.pemilih_id = p.id
            GROUP BY c.id, c.nama, c.keterangan
            ORDER BY jumlah_suara DESC
        ")->fetchAll(PDO::FETCH_ASSOC);

        return $stats;
    }

    // -------------------------------------------------------------------------
    // [PERBAIKAN] FUNGSI AMBIL PILIHAN (SESUAI NAMA TABEL & KOLOM ANDA)
    // -------------------------------------------------------------------------
    public function getPilihanByPemilih($pemilih_id)
    {
        // 1. Ambil Data Pareses
        // Perbaikan: 
        // - Kolom join: v.calon_pareses_id (Bukan v.calon_id)
        // - Kolom nama: c.nama (Bukan c.nama_calon) -> Kita alias-kan jadi nama_calon agar view tidak error
        $stmt1 = $this->db->prepare("
            SELECT c.nama as nama_calon, 'Pareses' as kategori 
            FROM vote_pareses v 
            JOIN calon_pareses c ON v.calon_pareses_id = c.id 
            WHERE v.pemilih_id = :pid
        ");
        $stmt1->execute(['pid' => $pemilih_id]);
        $pareses = $stmt1->fetchAll(PDO::FETCH_ASSOC);

        // 2. Ambil Data Majelis
        // Perbaikan: 
        // - Nama Tabel: vote_majelis_pusat & calon_majelis_pusat
        // - Kolom join: v.calon_majelis_pusat_id
        $stmt2 = $this->db->prepare("
            SELECT c.nama as nama_calon, 'Majelis Pusat' as kategori 
            FROM vote_majelis_pusat v 
            JOIN calon_majelis_pusat c ON v.calon_majelis_pusat_id = c.id 
            WHERE v.pemilih_id = :pid
        ");
        $stmt2->execute(['pid' => $pemilih_id]);
        $majelis = $stmt2->fetchAll(PDO::FETCH_ASSOC);

        // 3. Ambil Data BPK
        // Perbaikan:
        // - Kolom join: v.calon_bpk_id
        $stmt3 = $this->db->prepare("
            SELECT c.nama as nama_calon, 'BPK' as kategori 
            FROM vote_bpk v 
            JOIN calon_bpk c ON v.calon_bpk_id = c.id 
            WHERE v.pemilih_id = :pid
        ");
        $stmt3->execute(['pid' => $pemilih_id]);
        $bpk = $stmt3->fetchAll(PDO::FETCH_ASSOC);

        // Gabungkan
        return array_merge($pareses, $majelis, $bpk);
    }
}
