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
}