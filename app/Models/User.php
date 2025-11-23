<?php
// app/Models/User.php
namespace App\Models;

use PDO;

class User
{
    private $db;
    public function __construct($db)
    {
        $this->db = $db;
    }

    public function findByUsernameOrEmail($usernameOrEmail)
    {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE (username = :ue OR email = :ue) AND is_active = 1 LIMIT 1");
        $stmt->execute(['ue' => $usernameOrEmail]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
