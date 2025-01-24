<?php

namespace Controllers;

use Models\UserModel;

class RegisterController
{
    public function index()
    {
        include('src/app/Views/public/register.php');
    }

    public function register()
    {
        $userModel = new UserModel();

        $data = [
            'pseudo_membre' => $_POST['pseudo_membre'] ?? '',
            'email' => $_POST['email'] ?? '',
            'motdepasse' => $_POST['password'] ?? '',
            'adresse' => $_POST['adresse'] ?? '',
        ];

        if ($userModel->register($data)) {
            header('Location: login');
        } else {
            $error = 'Erreur lors de l’inscription.';
            include('src/app/Views/public/register.php');
        }
    }
}