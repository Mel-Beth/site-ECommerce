<?php

namespace Controllers;

use Models\UserModel;

class AuthController
{
    public function index()
    {
        if (isset($_SESSION['user'])) {
            header('Location: accueil');
            exit();
        }
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

        if (!empty($email) && !empty($password)) {
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $error = "Format d'email invalide.";
            } else {
                try {
                    $user = $userModel->authenticate($email, $password);
                    if ($user) {
                        $_SESSION['user'] = $user;

                        // Redirection en fonction du rôle de l'utilisateur
                        if ($user['id_role'] == 1) {
                            header('Location: dashboard');
                        } else {
                            header('Location: accueil');
                        }
                        exit();
                    } else {
                        $error = "Email ou mot de passe incorrect.";
                    }
                } catch (\Exception $e) {
                    $error = "Erreur de connexion : " . $e->getMessage();
                }
            }
        } else {
            $error = "Les champs email et mot de passe sont obligatoires.";
        }

        $_SESSION['login_error'] = $error;
        header('Location: login');
        exit();
    }

    public function register()
    {
        $userModel = new UserModel();
        $data = [
            'pseudo_membre' => $_POST['pseudo_membre'] ?? '',
            'email' => $_POST['email'] ?? '',
            'motdepasse' => $_POST['password'] ?? '',
        ];

        if ($userModel->register($data)) {
            header('Location: login');
        } else {
            $error = 'Erreur lors de l’inscription.';
            include('src/app/Views/public/register.php');
        }
    }
}
