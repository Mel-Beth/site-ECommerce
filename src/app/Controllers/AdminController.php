<?php

namespace Controllers;

use Models\UserModel;

class AdminController
{
    public function index()
    {
        // Vérifie si l'utilisateur est admin
        if (!isset($_SESSION['user']) || $_SESSION['user']['id_role'] !== 1) {
            header('Location: admin');
            exit();
        }

        include("src/app/Views/admin/dashboard.php");
    }
}
