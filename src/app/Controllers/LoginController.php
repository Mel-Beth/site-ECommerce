<?php

namespace Controllers;

use Models\UserModel;

class LoginController
{
    public function index()
    {
        // Vérifier si l'utilisateur est déjà connecté
        if (isset($_SESSION['user'])) {
            header('Location: accueil');
            exit();
        }

        // Affichage de la page de connexion
        $error = $_SESSION['login_error'] ?? '';
        unset($_SESSION['login_error']);

        include('src/app/Views/public/login.php');
    }

    public function login()
    {
        $userModel = new UserModel();

        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        $error = '';

        // Vérification des tentatives de connexion
        if (isset($_SESSION['login_attempts']) && $_SESSION['login_attempts'] >= 3) {
            $error = "Trop de tentatives de connexion. Veuillez réessayer plus tard.";
        }

        if (!empty($email) && !empty($password)) {
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $error = "Format d'email invalide.";
            } else {
                try {
                    $user = $userModel->authenticate($email, $password);

                    if ($user) {
                        // Stockage de l'utilisateur dans la session
                        $_SESSION['user'] = [
                            'id_membre' => $user['id_membre'],
                            'pseudo_membre' => $user['pseudo_membre'],
                            'email' => $user['email'],
                            'id_role' => $user['id_role'], // Rôle de l'utilisateur
                        ];

                        // Réinitialisation des tentatives
                        unset($_SESSION['login_attempts']);

                        // Redirection selon le rôle
                        if ($user['id_role'] === 1) {
                            header('Location: admin/dashboard');
                        } else {
                            header('Location: accueil');
                        }
                        exit();
                    } else {
                        $error = "Email ou mot de passe incorrect.";
                        $_SESSION['login_attempts'] = ($_SESSION['login_attempts'] ?? 0) + 1;
                    }
                } catch (\PDOException $e) {
                    $error = "Erreur lors de la connexion à la base de données : " . $e->getMessage();
                }
            }
        } else {
            $error = "Les champs email et mot de passe sont obligatoires.";
        }

        // Gestion des erreurs
        if (!empty($error)) {
            $_SESSION['login_error'] = $error;
            header('Location: login');
            exit();
        }
    }
}