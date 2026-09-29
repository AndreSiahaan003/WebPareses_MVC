<?php
class Calon extends Model
{
    // --- CALON PARESES ---
    public function getAllPareses()
    {
        $stmt = $this->db->query("SELECT * FROM calon_pareses ORDER BY nama ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function findPareses($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM calon_pareses WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    public function createPareses($nama, $daerah, $biodata, $lama_jabatan, $riwayat_kerja, $foto)
    {
        $stmt = $this->db->prepare("INSERT INTO calon_pareses (nama, daerah, biodata, lama_jabatan, riwayat_kerja, foto) VALUES (?, ?, ?, ?, ?, ?)");
        return $stmt->execute([$nama, $daerah, $biodata, $lama_jabatan, $riwayat_kerja, $foto]);
    }
    public function updatePareses($id, $nama, $daerah, $biodata, $lama_jabatan, $riwayat_kerja, $foto)
    {
        $stmt = $this->db->prepare("UPDATE calon_pareses SET nama = ?, daerah = ?, biodata = ?, lama_jabatan = ?, riwayat_kerja = ?, foto = ? WHERE id = ?");
        return $stmt->execute([$nama, $daerah, $biodata, $lama_jabatan, $riwayat_kerja, $foto, $id]);
    }
    public function deletePareses($id)
    {
        $stmt = $this->db->prepare("DELETE FROM calon_pareses WHERE id = ?");
        return $stmt->execute([$id]);
    }

    // --- CALON MAJELIS PUSAT ---
    public function getAllMajelisPusat()
    {
        $stmt = $this->db->query("SELECT * FROM calon_majelis_pusat ORDER BY nama ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function findMajelisPusat($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM calon_majelis_pusat WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    public function createMajelisPusat($nama, $keterangan, $biodata, $lama_jabatan, $riwayat_kerja, $foto)
    {
        $stmt = $this->db->prepare("INSERT INTO calon_majelis_pusat (nama, keterangan, biodata, lama_jabatan, riwayat_kerja, foto) VALUES (?, ?, ?, ?, ?, ?)");
        return $stmt->execute([$nama, $keterangan, $biodata, $lama_jabatan, $riwayat_kerja, $foto]);
    }
    public function updateMajelisPusat($id, $nama, $keterangan, $biodata, $lama_jabatan, $riwayat_kerja, $foto)
    {
        $stmt = $this->db->prepare("UPDATE calon_majelis_pusat SET nama = ?, keterangan = ?, biodata = ?, lama_jabatan = ?, riwayat_kerja = ?, foto = ? WHERE id = ?");
        return $stmt->execute([$nama, $keterangan, $biodata, $lama_jabatan, $riwayat_kerja, $foto, $id]);
    }
    public function deleteMajelisPusat($id)
    {
        $stmt = $this->db->prepare("DELETE FROM calon_majelis_pusat WHERE id = ?");
        return $stmt->execute([$id]);
    }

    // --- CALON BPK ---
    public function getAllBPK()
    {
        $stmt = $this->db->query("SELECT * FROM calon_bpk ORDER BY nama ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function findBPK($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM calon_bpk WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    public function createBPK($nama, $keterangan, $biodata, $lama_jabatan, $riwayat_kerja, $foto)
    {
        $stmt = $this->db->prepare("INSERT INTO calon_bpk (nama, keterangan, biodata, lama_jabatan, riwayat_kerja, foto) VALUES (?, ?, ?, ?, ?, ?)");
        return $stmt->execute([$nama, $keterangan, $biodata, $lama_jabatan, $riwayat_kerja, $foto]);
    }
    public function updateBPK($id, $nama, $keterangan, $biodata, $lama_jabatan, $riwayat_kerja, $foto)
    {
        $stmt = $this->db->prepare("UPDATE calon_bpk SET nama = ?, keterangan = ?, biodata = ?, lama_jabatan = ?, riwayat_kerja = ?, foto = ? WHERE id = ?");
        return $stmt->execute([$nama, $keterangan, $biodata, $lama_jabatan, $riwayat_kerja, $foto, $id]);
    }
    public function deleteBPK($id)
    {
        $stmt = $this->db->prepare("DELETE FROM calon_bpk WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
