<?php

namespace Controllers;

use Models\UserModel;

class RegisterController
{
    public function index()
    {
        // Vérifier si l'utilisateur est déjà connecté
        if (isset($_SESSION['user'])) {
            header('Location: accueil');
            exit();
        }

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

        // Validation des données
        if (empty($data['pseudo_membre']) || empty($data['email']) || empty($data['motdepasse'])) {
            $error = 'Tous les champs sont obligatoires.';
            include('src/app/Views/public/register.php');
            return;
        }

        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $error = 'Format d\'email invalide.';
            include('src/app/Views/public/register.php');
            return;
        }

        if ($userModel->isEmailOrPseudoUsed($data['email'], $data['pseudo_membre'])) {
            $error = 'Cet email ou ce pseudo est déjà utilisé.';
            include('src/app/Views/public/register.php');
            return;
        }

        if ($userModel->register($data)) {
            // Envoyer un email de confirmation
            $this->sendConfirmationEmail($data['email']);

            header('Location: login');
        } else {
            $error = 'Erreur lors de l’inscription.';
            include('src/app/Views/public/register.php');
        }
    }

    private function sendConfirmationEmail($email)
    {
        // Exemple d'envoi d'email avec PHPMailer ou une autre bibliothèque
        $subject = "Confirmation d'inscription";
        $message = "Merci de vous être inscrit sur notre site. Veuillez confirmer votre email en cliquant sur ce lien : [lien de confirmation]";

        // Utilisation de la fonction mail() de PHP (à adapter selon vos besoins)
        $headers = "From: no-reply@votre-site.com\r\n";
        $headers .= "Content-Type: text/html; charset=UTF-8\r\n";

        if (mail($email, $subject, $message, $headers)) {
            return true;
        } else {
            return false;
        }
    }
}
