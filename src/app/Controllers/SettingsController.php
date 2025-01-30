<?php
namespace Controllers;

use Models\SettingsModel;
use Models\UserModel;

class SettingsController
{
    private $settingsModel;

    public function __construct()
    {
        $this->settingsModel = new SettingsModel();
    }

    public function index()
    {
        if (!isset($_SESSION['user']) || $_SESSION['user']['id_role'] != 1) {
            header('Location: login');
            exit();
        }

        // Récupérer les valeurs actuelles des paramètres
        $tva = $this->settingsModel->getSetting('tva') ?? '20';
        $livraison = $this->settingsModel->getSetting('livraison') ?? '5';
        $maintenance = $this->settingsModel->getSetting('maintenance') ?? '0';

        include('src/app/Views/admin/settings.php');
    }

    public function updateSettings()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->settingsModel->updateSetting('tva', $_POST['tva'] ?? '20');
            $this->settingsModel->updateSetting('livraison', $_POST['livraison'] ?? '5');
            $this->settingsModel->updateSetting('maintenance', isset($_POST['maintenance']) ? '1' : '0');

            header('Location: admin/settings');
            exit();
        }
    }

    public function updatePassword()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userModel = new UserModel();
            $adminId = $_SESSION['user']['id_membre'];

            $currentPassword = $_POST['current_password'];
            $newPassword = $_POST['new_password'];
            $confirmPassword = $_POST['confirm_password'];

            if ($newPassword !== $confirmPassword) {
                $error = "Les mots de passe ne correspondent pas.";
            } elseif ($userModel->checkPassword($adminId, $currentPassword)) {
                $userModel->updatePassword($adminId, password_hash($newPassword, PASSWORD_BCRYPT));
                $success = "Mot de passe mis à jour avec succès.";
            } else {
                $error = "Mot de passe actuel incorrect.";
            }

            include('src/app/Views/admin/settings.php');
        }
    }
}
