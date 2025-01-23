<?php

namespace Admin\Controllers;

use Models\ModeleParent; // Importer le modèle parent

class AdminController
{
    private $modeleParent;

    public function __construct()
    {
        // Initialiser le modèle parent pour la connexion à la base de données
        $this->modeleParent = new ModeleParent();
    }

    public function index()
    {
        // Afficher la page de connexion admin
        require __DIR__ . '/../Views/admin.php';
    }

    public function login()
    {
        // Vérifier les identifiants
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        // Requête pour vérifier les identifiants dans la base de données
        $sql = "SELECT * FROM membres WHERE email = :email AND id_role = 1"; // 1 = Admin
        $stmt = $this->modeleParent->query($sql, ['email' => $email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['motdepasse'])) {
            // Authentification réussie
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_email'] = $user['email']; // Stocker l'email de l'admin en session (optionnel)
            header('Location: /admin/dashboard');
            exit();
        } else {
            // Authentification échouée
            $_SESSION['error'] = "Identifiants incorrects.";
            header('Location: /admin');
            exit();
        }
    }
}
