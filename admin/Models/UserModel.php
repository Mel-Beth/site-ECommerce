<?php
namespace Admin\Models;

use Models\ModeleParent;

class UserModel extends ModeleParent
{
    public function getAllUsers()
    {
        $stmt = $this->pdo->query("
            SELECT m.id_membre, m.pseudo_membre, m.email, r.lib_role
            FROM membres m
            JOIN roles r ON m.id_role = r.id_role
        ");
        return $stmt->fetchAll();
    }

    public function updateUserRole($userId, $role)
    {
        $stmt = $this->pdo->prepare("
            UPDATE membres SET id_role = :role WHERE id_membre = :id
        ");
        $stmt->execute(['role' => $role, 'id' => $userId]);
    }
}