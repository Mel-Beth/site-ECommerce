<?php

namespace Controllers;

use Models\UserModel;

class UserController
{
    public function profile()
    {
        if (!isset($_SESSION['user'])) {
            header('Location: login');
            exit();
        }

        $userModel = new UserModel();
        $user_id = $_SESSION['user']['id_membre'];

        try {
            $user = $userModel->getUserById($user_id);

            if (!$user) {
                header('Location: logout');
                exit();
            }

            // Récupérer les commandes de l'utilisateur
            $orders = $userModel->getUserOrders($user_id);

            // Récupérer les adresses de l'utilisateur
            $addresses = $userModel->getUserAddresses($user_id);

            include('src/app/Views/public/user.php');
        } catch (\PDOException $e) {
            $error = "Erreur lors de la récupération des informations : " . $e->getMessage();
            include('src/app/Views/404.php');
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