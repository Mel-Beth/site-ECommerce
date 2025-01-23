<?php

namespace Controllers;

use Models\UserModel;

class LoginController
{
    public function login()
    {
        // Traitement du formulaire de connexion
        $userModel = new UserModel();

        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        $error = '';

        // Validation des champs
        if (!empty($email) && !empty($password)) {
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $error = "Format d'email invalide.";
            } else {
                try {
                    // Recherche de l'utilisateur dans la base de données
                    $user = $userModel->authenticate($email, $password);

                    if ($user) {
                        // Stockage des informations de l'utilisateur dans la session
                        $_SESSION['user'] = [
                            'id_membre' => $user['id_membre'],
                            'pseudo_membre' => $user['pseudo_membre'],
                            'email' => $user['email'],
                            'id_role' => $user['id_role'],
                        ];

                        // Redirection en fonction du rôle
                        if ($user['id_role'] === 2) { // Client
                            header('Location: accueil');
                            exit();
                        } else {
                            $error = "Rôle utilisateur non reconnu.";
                        }
                    } else {
                        $error = "Email ou mot de passe incorrect.";
                    }
                } catch (\PDOException $e) {
                    $error = "Erreur lors de la connexion à la base de données : " . $e->getMessage();
                }
            }
        } else {
            $error = "Les champs email et mot de passe sont obligatoires.";
        }

        // Stockage de l'erreur dans la session et redirection vers la page de connexion
        if (!empty($error)) {
            $_SESSION['login_error'] = $error;
            header('Location: login');
            exit();
        }
    }
}
