<?php
namespace Models;

class SettingsModel extends ModeleParent
{
    public function getSetting($key)
    {
        $stmt = $this->pdo->prepare("SELECT key_value FROM settings WHERE key_name = :key");
        $stmt->execute(['key' => $key]);
        $result = $stmt->fetch();
        return $result ? $result['key_value'] : null;
    }

    public function updateSetting($key, $value)
    {
        $stmt = $this->pdo->prepare("INSERT INTO settings (key_name, key_value) VALUES (:key, :value)
            ON DUPLICATE KEY UPDATE key_value = :value");
        return $stmt->execute(['key' => $key, 'value' => $value]);
    }
}
