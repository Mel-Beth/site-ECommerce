<?php
namespace Models;

class UserModel extends ModeleParent
{
    public function authenticate($email, $password)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM membres WHERE email = :email");
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['motdepasse'])) {
            return $user;
        }

        return false;
    }

    public function register($data)
    {
        $stmt = $this->pdo->prepare("INSERT INTO membres (pseudo_membre, email, motdepasse, date_inscription, adresse, id_role) VALUES (:pseudo_membre, :email, :motdepasse, NOW(), :adresse, :id_role)");
        $data['motdepasse'] = password_hash($data['motdepasse'], PASSWORD_BCRYPT);
        $data['id_role'] = 2; // Par défaut, on attribue le rôle de client (id_role = 2)

        return $stmt->execute($data);
    }

    public function getUserById($userId)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM membres WHERE id_membre = :id_membre");
        $stmt->execute(['id_membre' => $userId]);
        return $stmt->fetch();
    }

    public function getUserOrders($userId)
    {
        $stmt = $this->pdo->prepare("
            SELECT c.id_commande, c.date_commande, c.montant_ttc 
            FROM commandes c
            WHERE c.id_membre = :id_membre
            ORDER BY c.date_commande DESC
        ");
        $stmt->execute(['id_membre' => $userId]);
        return $stmt->fetchAll();
    }
}