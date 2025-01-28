<?php

namespace Models;

use Models\ModeleParent;

class AdminModel extends ModeleParent
{
    public function getAdminByUsername($username)
    {
        $stmt = $this->pdo->prepare("
            SELECT * FROM membres WHERE pseudo_membre = :username AND id_role = 1
        ");
        $stmt->execute(['username' => $username]);
        return $stmt->fetch();
    }

    public function addAdmin($data)
    {
        $sql = "INSERT INTO membres (pseudo_membre, email, motdepasse, date_inscription, id_role) VALUES (:pseudo_membre, :email, :motdepasse, NOW(), 1)";
        $data['motdepasse'] = password_hash($data['motdepasse'], PASSWORD_BCRYPT);
        return $this->query($sql, $data);
    }
}