<?php

namespace Controllers;

use Models\ModeleParent;

class AdminController
{
    private $modeleParent;

    public function __construct()
    {
        $this->modeleParent = new ModeleParent();
    }

    public function index()
    {
        include("src/app/Views/admin/index.php");

    }

    public function login()
    {
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        $sql = "SELECT * FROM membres WHERE email = :email AND id_role = 1";
        $stmt = $this->modeleParent->query($sql, ['email' => $email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['motdepasse'])) {
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_email'] = $user['email'];
            header('Location: admin/dashboard');
            exit();
        } else {
            $_SESSION['error'] = "Identifiants incorrects.";
            header('Location: admin');
            exit();
        }
    }
}