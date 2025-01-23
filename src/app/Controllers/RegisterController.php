<?php

namespace Controllers;

use Models\UserModel;

class RegisterController
{
    public function index()
    {
        include PROJECT_ROOT . '/src/app/Views/register.php';
    }

    public function register()
    {
        $userModel = new UserModel();

        $data = [
            'pseudo_membre' => $_POST['pseudo_membre'] ?? '', // Correction ici
            'email' => $_POST['email'] ?? '', // Correction ici
            'motdepasse' => $_POST['motdepasse'] ?? '', // Correction ici
            'adresse' => $_POST['adresse'] ?? '', // Correction ici
        ];

        if ($userModel->register($data)) {
            header('Location: login.php');
        } else {
            $error = 'Erreur lors de l’inscription.';
            include PROJECT_ROOT . '/src/app/Views/register.php';
        }
    }
}