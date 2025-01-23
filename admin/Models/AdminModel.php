<?php
namespace Admin\Models;

use Models\ModeleParent;

class AdminModel extends ModeleParent
{
    // Méthodes spécifiques à l'admin
    public function getAdminByUsername($username)
    {
        $stmt = $this->pdo->prepare("
            SELECT * FROM membres WHERE pseudo_membre = :username AND id_role = 1
        ");
        $stmt->execute(['username' => $username]);
        return $stmt->fetch();
    }
}