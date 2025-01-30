<?php

namespace Models;

use Models\ModeleParent;

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
        try {
            // Si aucune adresse n'est fournie, on peut la définir comme une chaîne vide
            $data['adresse'] = $data['adresse'] ?? '';

            // Préparer la requête d'insertion
            $stmt = $this->pdo->prepare("INSERT INTO membres (pseudo_membre, email, motdepasse, date_inscription, adresse, id_role, is_active) 
                                     VALUES (:pseudo_membre, :email, :motdepasse, NOW(), :adresse, :id_role, :is_active)");

            // Hash du mot de passe avant insertion
            $data['motdepasse'] = password_hash($data['motdepasse'], PASSWORD_BCRYPT);
            $data['id_role'] = 2; // Par défaut, on attribue le rôle de client (id_role = 2)
            $data['is_active'] = 1; // L'utilisateur est actif par défaut

            // Exécuter la requête
            $result = $stmt->execute($data);

            // Retourner le résultat
            return $result;
        } catch (\Exception $e) {
            // Si une erreur survient, afficher l'exception
            var_dump($e->getMessage());
            return false;
        }
    }

    // Dans UserModel.php
public function emailExists($email)
{
    // Préparer la requête SQL pour vérifier si un utilisateur avec l'email existe
    $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM membres WHERE email = :email");
    $stmt->execute(['email' => $email]);
    $result = $stmt->fetchColumn();

    // Si le résultat est supérieur à 0, cela signifie que l'email existe
    return $result > 0;
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

    public function updateUserRole($userId, $role)
    {
        $stmt = $this->pdo->prepare("
            UPDATE membres SET id_role = :role WHERE id_membre = :id
        ");
        $stmt->execute(['role' => $role, 'id' => $userId]);
    }

    public function isEmailOrPseudoUsed($email, $pseudo)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM membres WHERE email = :email OR pseudo_membre = :pseudo");
        $stmt->execute(['email' => $email, 'pseudo' => $pseudo]);
        return $stmt->fetch() !== false;
    }

    public function getUserAddresses($userId)
    {
        $sql = "SELECT * FROM adresses WHERE id_membre = :id_membre";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id_membre' => $userId]);
        return $stmt->fetchAll();
    }

    public function addAddress($userId, $address)
    {
        $sql = "INSERT INTO adresses (id_membre, adresse) VALUES (:id_membre, :adresse)";
        return $this->query($sql, ['id_membre' => $userId, 'adresse' => $address]);
    }

    public function updateUserInfo($userId, $pseudo, $email)
    {
        $stmt = $this->pdo->prepare("
        UPDATE membres
        SET pseudo_membre = :pseudo_membre, email = :email
        WHERE id_membre = :id_membre
    ");
        return $stmt->execute([
            'pseudo_membre' => $pseudo,
            'email' => $email,
            'id_membre' => $userId
        ]);
    }

    public function getAllUsers()
    {
        $stmt = $this->pdo->query("
        SELECT m.id_membre, m.pseudo_membre, m.email, m.id_role, m.is_active, r.lib_role 
        FROM membres m
        JOIN roles r ON m.id_role = r.id_role
    ");
        return $stmt->fetchAll();
    }

    public function checkPassword($userId, $password)
    {
        $stmt = $this->pdo->prepare("SELECT motdepasse FROM membres WHERE id_membre = :id");
        $stmt->execute(['id' => $userId]);
        $user = $stmt->fetch();

        return password_verify($password, $user['motdepasse']);
    }

    public function updatePassword($userId, $newPassword)
    {
        $stmt = $this->pdo->prepare("UPDATE membres SET motdepasse = :password WHERE id_membre = :id");
        return $stmt->execute(['password' => $newPassword, 'id' => $userId]);
    }
}
