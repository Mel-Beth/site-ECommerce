<?php

namespace Models;

use Models\ModeleParent;

class OrderModel extends ModeleParent
{
    public function getDailyOrders()
    {
        $stmt = $this->pdo->query("
        SELECT DATE(date_commande) as date, COUNT(*) as commandes, SUM(montant_ttc) as revenus
        FROM commandes
        GROUP BY DATE(date_commande)
        ORDER BY DATE(date_commande) ASC
    ");
        return $stmt->fetchAll();
    }

    public function getTotalRevenue()
    {
        $stmt = $this->pdo->query("SELECT SUM(montant_ttc) as total FROM commandes");
        $result = $stmt->fetch();
        return $result['total'];
    }

    public function getPendingOrdersCount()
    {
        $stmt = $this->pdo->query("SELECT COUNT(*) as total FROM commandes WHERE statut_preparation = 0");
        $result = $stmt->fetch();
        return $result['total'];
    }

    public function getOrderItems($orderId)
    {
        $sql = "SELECT * FROM contenir WHERE id_commande = :id_commande";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id_commande' => $orderId]);
        return $stmt->fetchAll();
    }
    public function getAllOrders()
    {
        $stmt = $this->pdo->query("
            SELECT c.id_commande, c.date_commande, c.statut_preparation, c.montant_ttc, m.pseudo_membre
            FROM commandes c
            JOIN membres m ON c.id_membre = m.id_membre
            ORDER BY c.date_commande DESC
        ");
        return $stmt->fetchAll();
    }

    public function updateOrderStatus($orderId, $status)
    {
        $stmt = $this->pdo->prepare("
            UPDATE commandes SET statut_preparation = :status WHERE id_commande = :id
        ");
        $stmt->execute(['status' => $status, 'id' => $orderId]);
    }

    public function getMonthlyRevenue()
    {
        $sql = "SELECT DATE_FORMAT(date_commande, '%Y-%m') as mois, SUM(montant_ttc) as revenus FROM commandes GROUP BY mois ORDER BY mois";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll();
    }

    public function getOrderById($orderId)
    {
        $sql = "SELECT * FROM commandes WHERE id_commande = :id_commande";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id_commande' => $orderId]);
        return $stmt->fetch();
    }
}
