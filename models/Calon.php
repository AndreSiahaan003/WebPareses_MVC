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
    public function createPareses($nama, $daerah)
    {
        $stmt = $this->db->prepare("INSERT INTO calon_pareses (nama, daerah) VALUES (?, ?)");
        return $stmt->execute([$nama, $daerah]);
    }
    public function updatePareses($id, $nama, $daerah)
    {
        $stmt = $this->db->prepare("UPDATE calon_pareses SET nama = ?, daerah = ? WHERE id = ?");
        return $stmt->execute([$nama, $daerah, $id]);
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
    public function createMajelisPusat($nama, $keterangan)
    {
        $stmt = $this->db->prepare("INSERT INTO calon_majelis_pusat (nama, keterangan) VALUES (?, ?)");
        return $stmt->execute([$nama, $keterangan]);
    }
    public function updateMajelisPusat($id, $nama, $keterangan)
    {
        $stmt = $this->db->prepare("UPDATE calon_majelis_pusat SET nama = ?, keterangan = ? WHERE id = ?");
        return $stmt->execute([$nama, $keterangan, $id]);
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
    public function createBPK($nama, $keterangan)
    {
        $stmt = $this->db->prepare("INSERT INTO calon_bpk (nama, keterangan) VALUES (?, ?)");
        return $stmt->execute([$nama, $keterangan]);
    }
    public function updateBPK($id, $nama, $keterangan)
    {
        $stmt = $this->db->prepare("UPDATE calon_bpk SET nama = ?, keterangan = ? WHERE id = ?");
        return $stmt->execute([$nama, $keterangan, $id]);
    }
    public function deleteBPK($id)
    {
        $stmt = $this->db->prepare("DELETE FROM calon_bpk WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
