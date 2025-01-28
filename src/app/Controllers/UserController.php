<?php

namespace Controllers;

use Models\UserModel;

class UserController
{
    public function profile()
    {
        if (!isset($_SESSION['user'])) {
            header('Location: login');
            exit();
        }
        $userModel = new UserModel();
        $user = $userModel->getUserById($_SESSION['user']['id_membre']);
        include('src/app/Views/public/user.php');
    }

    public function updateRole($userId, $role)
    {
        $userModel = new UserModel();
        $userModel->updateUserRole($userId, $role);
        header('Location: admin/users');
        exit();
    }
}
?>