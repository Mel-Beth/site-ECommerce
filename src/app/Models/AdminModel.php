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
}