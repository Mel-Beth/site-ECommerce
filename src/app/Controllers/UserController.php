<?php

namespace Controllers;

use Models\UserModel;

class UserController
{
    public function profile()
    {
        // Vérification si l'utilisateur est connecté
        if (!isset($_SESSION['user'])) {
            header('Location: login');
            exit();
        }

        $userModel = new UserModel();
        $user_id = $_SESSION['user']['id_membre'];

        try {
            // Récupération des informations de l'utilisateur
            $user = $userModel->getUserById($user_id);

            if (!$user) {
                // Si l'utilisateur n'existe pas, rediriger vers la déconnexion
                header('Location: logout');
                exit();
            }

            // Récupération des commandes de l'utilisateur
            $orders = $userModel->getUserOrders($user_id);

            // Passer les données à la vue
            include('src/app/Views/public/user.php');
        } catch (\PDOException $e) {
            // Gestion des erreurs
            $error = "Erreur lors de la récupération des informations : " . $e->getMessage();
            include('src/app/Views/public/error.php');
        }
    }

    public function updateRole($userId, $role)
    {
        $userModel = new UserModel();
        $userModel->updateUserRole($userId, $role);
        header('Location: admin/users');
        exit();
    }
}

?>