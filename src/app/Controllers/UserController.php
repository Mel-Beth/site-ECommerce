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

        // Gestion de la mise à jour des informations utilisateur
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $pseudo = $_POST['pseudo_membre'] ?? '';
            $email = $_POST['email'] ?? '';

            if (empty($pseudo) || empty($email)) {
                $error = "Veuillez remplir tous les champs.";
            } else {
                $isUpdated = $userModel->updateUserInfo($_SESSION['user']['id_membre'], $pseudo, $email);
                if ($isUpdated) {
                    $success = "Vos informations ont été mises à jour avec succès.";
                    $_SESSION['user']['pseudo_membre'] = $pseudo; // Met à jour la session
                    $_SESSION['user']['email'] = $email; // Met à jour la session
                } else {
                    $error = "Une erreur est survenue lors de la mise à jour.";
                }
            }
        }

        $user = $userModel->getUserById($_SESSION['user']['id_membre']);
        $orders = $userModel->getUserOrders($_SESSION['user']['id_membre']);
        include('src/app/Views/public/user.php');
    }

    public function listUsers()
    {
        if (!isset($_SESSION['user']) || $_SESSION['user']['id_role'] != 1) {
            header('Location: login');
            exit();
        }

        $userModel = new UserModel();
        $users = $userModel->getAllUsers();  // Récupère tous les utilisateurs

        // Inclure la vue pour afficher la liste des utilisateurs
        include('src/app/Views/admin/usersAdmin.php');
    }
}
