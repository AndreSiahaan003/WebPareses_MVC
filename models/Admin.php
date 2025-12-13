<?php
class Admin extends Model
{
    public function login($username, $password)
    {
        $stmt = $this->db->prepare("SELECT * FROM admin WHERE username = ?");
        $stmt->execute([$username]);
        $admin = $stmt->fetch(PDO::FETCH_ASSOC);

        // Kode asli
        if ($admin && password_verify($password, $admin['password'])) {
            return $admin;
        }

        return false;
    }
}
