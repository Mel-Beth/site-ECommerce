<?php

namespace Controllers;

use Models\UserModel;

class AuthController
{
    public function index()
    {
        // Si l'utilisateur est déjà connecté, redirige vers l'accueil
        if (isset($_SESSION['user'])) {
            header('Location: accueil');
            exit();
        }

        // Gérer les vues en fonction de la route demandée
        if ($_GET['route'] == 'login') {
            // Affiche le formulaire de connexion
            $error = $_SESSION['login_error'] ?? '';
            unset($_SESSION['login_error']);
            include('src/app/Views/public/login.php');
        } elseif ($_GET['route'] == 'register') {
            // Affiche le formulaire d'inscription
            $error = $_SESSION['register_error'] ?? '';
            unset($_SESSION['register_error']);
            include('src/app/Views/public/register.php');
        }
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
                            header('Location: admin/dashboard');
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
            'adresse' => $_POST['adresse'] ?? '', // Si l'adresse est vide, on la laisse comme ça
        ];

        // Affiche les données pour débogage
        // var_dump($data);

        // Vérifie si l'utilisateur existe déjà avec cet email
        if ($userModel->emailExists($data['email'])) {
            $_SESSION['register_error'] = "Cet email est déjà utilisé.";
            include('src/app/Views/public/register.php');
            return;
        }

        // Inscription de l'utilisateur
        if ($userModel->register($data)) {
            // Authentifie l'utilisateur immédiatement après l'inscription
            $user = $userModel->authenticate($data['email'], $data['motdepasse']);

            // Vérifie si l'utilisateur a été authentifié avec succès
            // var_dump($user);

            if ($user) {
                $_SESSION['user'] = $user; // Connexion automatique

                // Redirige vers la page d'accueil après connexion
                header('Location: accueil');
                exit();
            } else {
                $_SESSION['login_error'] = 'Problème lors de la connexion automatique.';
                header('Location: login');
                exit();
            }
        } else {
            $_SESSION['register_error'] = 'Erreur lors de l’inscription.';
            include('src/app/Views/public/register.php');
        }
    }

    public function forgotPassword()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';
            $userModel = new UserModel();

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $_SESSION['error'] = "Format d'email invalide.";
                header('Location: forgot-password');
                exit();
            }

            $user = $userModel->getUserByEmail($email);
            if (!$user) {
                $_SESSION['error'] = "Aucun compte associé à cet email.";
                header('Location: forgot-password');
                exit();
            }

            $token = bin2hex(random_bytes(50));
            $userModel->storeResetToken($user['id_membre'], $token);

            $resetLink = "http://yourwebsite.com/reset-password?token=" . $token;
            mail($email, "Réinitialisation du mot de passe", "Cliquez sur ce lien pour réinitialiser votre mot de passe : " . $resetLink);

            $_SESSION['success'] = "Un email de réinitialisation a été envoyé.";
            header('Location: login');
            exit();
        }
    }

    public function resetPassword()
    {
        $userModel = new UserModel();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $token = $_POST['token'] ?? '';
            $newPassword = $_POST['password'] ?? '';

            if (empty($token) || empty($newPassword)) {
                $_SESSION['error'] = "Tous les champs sont obligatoires.";
                header('Location: reset-password?token=' . $token);
                exit();
            }

            $user = $userModel->getUserByToken($token);
            if (!$user) {
                $_SESSION['error'] = "Token invalide ou expiré.";
                header('Location: forgot-password');
                exit();
            }

            $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);
            $userModel->updatePassword($user['id_membre'], $hashedPassword);
            $userModel->clearResetToken($user['id_membre']);

            $_SESSION['success'] = "Votre mot de passe a été mis à jour.";
            header('Location: login');
            exit();
        }
    }
}
