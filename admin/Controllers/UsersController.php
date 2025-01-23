<?php
namespace Admin\Controllers;

use Admin\Models\UserModel;

class UsersController
{
    public function index()
    {
        $userModel = new UserModel();
        $users = $userModel->getAllUsers();
        require __DIR__ . '/../Views/users.php';
    }

    public function updateRole($userId, $role)
    {
        $userModel = new UserModel();
        $userModel->updateUserRole($userId, $role);
        header('Location: /admin/users');
        exit();
    }
}