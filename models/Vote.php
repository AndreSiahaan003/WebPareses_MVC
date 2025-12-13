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

            // ------------------------------------------------------------------
            // [KEAMANAN KRITIS] CEK DAN KUNCI PEMILIH DI AWAL
            // ------------------------------------------------------------------
            // Kita mencoba update status menjadi 'Sudah Memilih' (1) DAN matikan Token (1).
            // Syaratnya: status_vote harus masih 0.
            // Jika baris yang terupdate = 0, berarti orang ini sudah duluan memilih di perangkat lain.

            $stmtLock = $this->db->prepare("UPDATE pemilih SET status_vote = 1, token_used = 1 WHERE id = ? AND status_vote = 0");
            $stmtLock->execute([$pemilih_id]);

            if ($stmtLock->rowCount() == 0) {
                // Batalkan semua, lempar error keras!
                throw new Exception("DOUBLE VOTE DETECTED: Suara Anda sudah terekam dari perangkat lain.");
            }

            // ------------------------------------------------------------------
            // JIKA LOLOS (Baru pertama kali), LANJUT SIMPAN SUARA
            // ------------------------------------------------------------------

            // 2. Simpan Pareses (Looping)
            $stmtPareses = $this->db->prepare("INSERT INTO vote_pareses (pemilih_id, calon_pareses_id) VALUES (?, ?)");
            foreach ($pareses_ids as $p_id) {
                $stmtPareses->execute([$pemilih_id, $p_id]);
            }

            // 3. Simpan Majelis (Looping)
            $stmtMajelis = $this->db->prepare("INSERT INTO vote_majelis_pusat (pemilih_id, calon_majelis_pusat_id) VALUES (?, ?)");
            foreach ($majelis_ids as $m_id) {
                $stmtMajelis->execute([$pemilih_id, $m_id]);
            }

            // 4. Simpan BPK (Looping)
            $stmtBPK = $this->db->prepare("INSERT INTO vote_bpk (pemilih_id, calon_bpk_id) VALUES (?, ?)");
            foreach ($bpk_ids as $b_id) {
                $stmtBPK->execute([$pemilih_id, $b_id]);
            }

            // 5. Commit (Simpan Permanen)
            $this->db->commit();
            return true;
        } catch (Exception $e) {
            // 6. Rollback (Batalkan SEMUA jika ada error)
            $this->db->rollBack();
            throw $e;
        }
    }

    /**
     * Mengambil Statistik Lengkap untuk Dashboard Admin
     * Termasuk rincian per Unsur (Pendeta, Sintua, Jemaat)
     */
    public function getStats()
    {
        $stats = [];

        // Query Helper untuk menghitung detail unsur (Pivot)
        // Ini diperlukan agar Modal Popup di Dashboard Admin muncul angkanya
        $selectBreakdown = "
            COUNT(v.id) as jumlah_suara,
            SUM(IF(p.unsur = 'Pendeta', 1, 0)) as suara_pendeta,
            SUM(IF(p.unsur = 'Anggota Jemaat', 1, 0)) as suara_jemaat,
            SUM(IF(p.unsur IN ('Guru Jemaat', 'Penatua'), 1, 0)) as suara_sintua,
            SUM(IF(p.unsur LIKE 'Lembaga%' OR p.unsur LIKE 'Ketua%' OR p.unsur LIKE 'UNSUR%', 1, 0)) as suara_lembaga
        ";

        // 1. STATISTIK PARESES
        $stats['pareses'] = $this->db->query("
            SELECT c.nama, c.daerah, $selectBreakdown
            FROM calon_pareses c
            LEFT JOIN vote_pareses v ON c.id = v.calon_pareses_id
            LEFT JOIN pemilih p ON v.pemilih_id = p.id
            GROUP BY c.id, c.nama, c.daerah
            ORDER BY jumlah_suara DESC
        ")->fetchAll(PDO::FETCH_ASSOC);

        // 2. STATISTIK MAJELIS
        $stats['majelis'] = $this->db->query("
            SELECT c.nama, c.keterangan, $selectBreakdown
            FROM calon_majelis_pusat c
            LEFT JOIN vote_majelis_pusat v ON c.id = v.calon_majelis_pusat_id
            LEFT JOIN pemilih p ON v.pemilih_id = p.id
            GROUP BY c.id, c.nama, c.keterangan
            ORDER BY jumlah_suara DESC
        ")->fetchAll(PDO::FETCH_ASSOC);

        // 3. STATISTIK BPK
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
}
