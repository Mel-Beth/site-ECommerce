<?php

namespace Models;

use Models\ModeleParent;

class OrderModel extends ModeleParent
{
    // Méthode pour récupérer les produits en stock bas
    // Ajoutez cette méthode dans OrderModel.php
    public function getLowStockProducts($threshold = 10)
    {
        $stmt = $this->pdo->prepare("
        SELECT lib_article, quantite_stock
        FROM articles
        WHERE quantite_stock <= :threshold
    ");
        $stmt->execute(['threshold' => $threshold]);
        return $stmt->fetchAll(); // Retourne les produits avec des stocks bas
    }


    // Les autres méthodes existantes comme getDailyOrders, getMonthlyRevenue, etc...
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

    public function getCategoryStats()
    {
        $stmt = $this->pdo->query("
        SELECT categorie, SUM(valeur) as count
        FROM statistiques
        WHERE type_statistique = 'Commandes par Catégorie'
        GROUP BY categorie
    ");
        return $stmt->fetchAll(); // Retourne les commandes par catégorie
    }


    public function getMonthlyRevenue()
    {
        // Récupérer les revenus mensuels en agrégeant les "Revenus Journaliers"
        $stmt = $this->pdo->query("
        SELECT DATE_FORMAT(date_statistique, '%Y-%m') AS mois, 
               SUM(valeur) AS revenus 
        FROM statistiques 
        WHERE type_statistique = 'Revenus Journaliers' 
        GROUP BY mois
        ORDER BY mois ASC
    ");
        return $stmt->fetchAll(); // Retourne les revenus mensuels agrégés
    }




    public function getOrderById($orderId)
    {
        $stmt = $this->pdo->prepare("
        SELECT c.id_commande, c.date_commande, c.statut_preparation, c.montant_ht, c.montant_ttc, c.adresse_facturation, c.adresse_livraison, m.pseudo_membre, m.email
        FROM commandes c
        JOIN membres m ON c.id_membre = m.id_membre
        WHERE c.id_commande = :id_commande
    ");
        $stmt->execute(['id_commande' => $orderId]);
        return $stmt->fetch();
    }

    // Ajoutez cette méthode dans OrderModel.php pour obtenir le total des commandes
    public function getTotalOrders()
    {
        $stmt = $this->pdo->query("SELECT COUNT(*) as total FROM commandes");
        $result = $stmt->fetch();
        return $result['total'];
    }

    // Méthode pour obtenir le nombre total d'articles en stock
    public function getTotalStock()
    {
        $stmt = $this->pdo->query("SELECT SUM(quantite_stock) AS total_stock FROM articles");
        $result = $stmt->fetch();
        return $result['total_stock'];
    }

    // Méthode pour obtenir le revenu moyen par commande
    public function getAverageRevenuePerOrder()
    {
        $stmt = $this->pdo->query("SELECT AVG(montant_ttc) AS avg_revenue FROM commandes");
        $result = $stmt->fetch();
        return $result['avg_revenue'];
    }

    // Méthode pour obtenir le nombre de nouveaux utilisateurs inscrits
    public function getNewUsersCount()
    {
        $stmt = $this->pdo->query("SELECT COUNT(*) AS new_users FROM membres WHERE date_inscription >= CURDATE() - INTERVAL 1 MONTH");
        $result = $stmt->fetch();
        return $result['new_users'];
    }

    // Méthode pour obtenir les 5 produits les plus vendus
    public function getTopSellingProducts()
    {
        $stmt = $this->pdo->query("
        SELECT a.lib_article, SUM(c.quantite) AS total_sold
        FROM contenir c
        JOIN articles a ON c.id_article = a.id_article
        GROUP BY c.id_article
        ORDER BY total_sold DESC
        LIMIT 5
    ");
        return $stmt->fetchAll(); // Retourne les 5 produits les plus vendus
    }
}
