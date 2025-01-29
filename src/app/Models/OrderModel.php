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

    public function getOrderDetails($orderId)
    {
        $stmt = $this->pdo->prepare("
        SELECT 
            c.id_commande, 
            c.date_commande, 
            c.statut_preparation, 
            c.montant_ht, 
            c.montant_ttc, 
            c.adresse_facturation, 
            c.adresse_livraison, 
            m.pseudo_membre
        FROM commandes c
        JOIN membres m ON c.id_membre = m.id_membre
        WHERE c.id_commande = :id_commande
    ");
        $stmt->execute(['id_commande' => $orderId]);
        return $stmt->fetch(); // Renvoie une seule ligne contenant les informations de la commande et du membre
    }

    public function getOrderItems($orderId)
    {
        $stmt = $this->pdo->prepare("
        SELECT a.id_article, a.lib_article, a.prix, c.quantite
        FROM articles a
        JOIN contenir c ON a.id_article = c.id_article
        WHERE c.id_commande = :id_commande
    ");
        $stmt->execute(['id_commande' => $orderId]);
        return $stmt->fetchAll(); // Renvoie tous les articles associés à la commande
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
        $stmt = $this->pdo->prepare("
        SELECT c.id_commande, c.date_commande, c.statut_preparation, c.montant_ht, c.montant_ttc, c.adresse_facturation, c.adresse_livraison, m.pseudo_membre
        FROM commandes c
        JOIN membres m ON c.id_membre = m.id_membre
        WHERE c.id_commande = :id_commande
    ");
        $stmt->execute(['id_commande' => $orderId]);
        return $stmt->fetch();
    }
}
