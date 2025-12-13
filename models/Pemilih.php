<?php
class Pemilih extends Model
{
    // --- FUNGSI REGISTRASI PUBLIK (JIKA MASIH DIPAKAI) ---
    public function register($nama, $unsur, $daerah_lembaga, $resort)
    {
        // Cek jika nama sudah ada
        $stmt_check = $this->db->prepare("SELECT id, status_vote FROM pemilih WHERE nama = ?");
        $stmt_check->execute([$nama]);
        $existing = $stmt_check->fetch(PDO::FETCH_ASSOC);

        if ($existing) {
            if ($existing['status_vote'] == 1) {
                throw new Exception("Nama pemilih ini sudah digunakan untuk memilih.");
            } else {
                return $existing['id'];
            }
        }

        // Generate Token untuk registrasi mandiri juga (Supaya tidak error di database)
        $token = bin2hex(random_bytes(16));

        // Jika benar-benar baru
        $sql = "INSERT INTO pemilih (nama, unsur, daerah_lembaga, resort, status_vote, token, token_used) 
                VALUES (?, ?, ?, ?, 0, ?, 0)";
        $stmt = $this->db->prepare($sql);

        if ($stmt->execute([$nama, $unsur, $daerah_lembaga, $resort, $token])) {
            return $this->db->lastInsertId();
        } else {
            throw new Exception("Gagal mendaftarkan pemilih.");
        }
    }

    // --- FUNGSI UTAMA ADMIN (CREATE DENGAN TOKEN) ---
    public function create($nama, $unsur, $daerah_lembaga, $resort)
    {
        // 1. Generate Token Unik (32 karakter acak)
        $token = bin2hex(random_bytes(16));

        try {
            // 2. Query Insert dengan Token
            // Default status_vote = 0, token_used = 0
            $sql = "INSERT INTO pemilih (nama, unsur, daerah_lembaga, resort, status_vote, token, token_used) 
                    VALUES (?, ?, ?, ?, 0, ?, 0)";

            $stmt = $this->db->prepare($sql);

            if ($stmt->execute([$nama, $unsur, $daerah_lembaga, $resort, $token])) {
                // PENTING: Kembalikan ID agar bisa di-redirect ke halaman QR Code
                return $this->db->lastInsertId();
            }
            return false;
        } catch (PDOException $e) {
            // Cek error Duplicate Entry (Nama atau Token kembar)
            if ($e->errorInfo[1] == 1062) {
                throw new Exception("Nama pemilih sudah ada di database.");
            } else {
                throw $e;
            }
        }
    }

    // --- FUNGSI PENDUKUNG LOGIN BARCODE ---

    // 1. Cari Pemilih berdasarkan Token (Saat Scan)
    public function findByToken($token)
    {
        $stmt = $this->db->prepare("SELECT * FROM pemilih WHERE token = ? LIMIT 1");
        $stmt->execute([$token]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // 2. Matikan Token (Setelah berhasil login/scan)
    public function invalidateToken($id)
    {
        $stmt = $this->db->prepare("UPDATE pemilih SET token_used = 1 WHERE id = ?");
        return $stmt->execute([$id]);
    }

    // --- FUNGSI STANDAR LAINNYA (TIDAK BERUBAH) ---
    public function setSudahMemilih($id)
    {
        // Update status_vote JADI 1 DAN token_used JADI 1 secara bersamaan
        $stmt = $this->db->prepare("UPDATE pemilih SET status_vote = 1, token_used = 1 WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function getAll()
    {
        $stmt = $this->db->query("SELECT * FROM pemilih ORDER BY nama ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function find($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM pemilih WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function update($id, $nama, $unsur, $daerah_lembaga, $resort)
    {
        try {
            $stmt = $this->db->prepare("UPDATE pemilih SET nama = ?, unsur = ?, daerah_lembaga = ?, resort = ? WHERE id = ?");
            return $stmt->execute([$nama, $unsur, $daerah_lembaga, $resort, $id]);
        } catch (PDOException $e) {
            if ($e->errorInfo[1] == 1062) {
                throw new Exception("Nama pemilih sudah ada di database.");
            } else {
                throw $e;
            }
        }
    }

    public function delete($id)
    {
        $stmt = $this->db->prepare("DELETE FROM pemilih WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
