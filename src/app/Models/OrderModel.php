<?php

namespace Models;

use Models\ModeleParent;

class OrderModel extends ModeleParent
{
    public function getLowStockProducts($threshold = 10)
    {
        $stmt = $this->pdo->prepare("SELECT lib_article, quantite_stock FROM articles WHERE quantite_stock <= :threshold");
        $stmt->execute(['threshold' => $threshold]);
        return $stmt->fetchAll();
    }

    public function getDailyOrders()
    {
        $stmt = $this->pdo->query("SELECT DATE(date_commande) as date, COUNT(*) as commandes, SUM(montant_ttc) as revenus FROM commandes GROUP BY DATE(date_commande) ORDER BY DATE(date_commande) ASC");
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
        $stmt = $this->pdo->query("SELECT c.id_commande, c.date_commande, c.statut_preparation, c.montant_ttc, m.pseudo_membre FROM commandes c JOIN membres m ON c.id_membre = m.id_membre ORDER BY c.date_commande DESC");
        return $stmt->fetchAll();
    }

    public function updateOrderStatus($orderId, $status)
    {
        $stmt = $this->pdo->prepare("UPDATE commandes SET statut_preparation = :status WHERE id_commande = :id");
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
        $stmt = $this->pdo->prepare("SELECT c.id_commande, c.date_commande, c.statut_preparation, c.montant_ht, c.montant_ttc, c.adresse_facturation, c.adresse_livraison, m.pseudo_membre FROM commandes c JOIN membres m ON c.id_membre = m.id_membre WHERE c.id_commande = :id_commande");
        $stmt->execute(['id_commande' => $orderId]);
        return $stmt->fetch();
    }

    public function getOrderById($orderId)
    {
        $stmt = $this->pdo->prepare("SELECT c.id_commande, c.date_commande, c.statut_preparation, c.montant_ht, c.montant_ttc, c.adresse_facturation, c.adresse_livraison, m.pseudo_membre, m.email FROM commandes c JOIN membres m ON c.id_membre = m.id_membre WHERE c.id_commande = :id_commande");
        $stmt->execute(['id_commande' => $orderId]);
        return $stmt->fetch();
    }

    public function getOrderItems($orderId)
    {
        $stmt = $this->pdo->prepare("SELECT a.id_article, a.lib_article, a.prix, c.quantite FROM articles a JOIN contenir c ON a.id_article = c.id_article WHERE c.id_commande = :id_commande");
        $stmt->execute(['id_commande' => $orderId]);
        return $stmt->fetchAll();
    }

    public function getCategoryStats()
    {
        $stmt = $this->pdo->query("SELECT categorie, SUM(valeur) as count FROM statistiques WHERE type_statistique = 'Commandes par Catégorie' GROUP BY categorie");
        return $stmt->fetchAll();
    }

    public function getMonthlyRevenue()
    {
        $stmt = $this->pdo->query("SELECT DATE_FORMAT(date_statistique, '%Y-%m') AS mois, SUM(valeur) AS revenus FROM statistiques WHERE type_statistique = 'Revenus Journaliers' GROUP BY mois ORDER BY mois ASC");
        return $stmt->fetchAll();
    }

    public function getTotalOrders()
    {
        $stmt = $this->pdo->query("SELECT COUNT(*) as total FROM commandes");
        $result = $stmt->fetch();
        return $result['total'];
    }

    public function getTotalStock()
    {
        $stmt = $this->pdo->query("SELECT SUM(quantite_stock) AS total_stock FROM articles");
        $result = $stmt->fetch();
        return $result['total_stock'];
    }

    public function getAverageRevenuePerOrder()
    {
        $stmt = $this->pdo->query("SELECT AVG(montant_ttc) AS avg_revenue FROM commandes");
        $result = $stmt->fetch();
        return $result['avg_revenue'];
    }

    public function getNewUsersCount()
    {
        $stmt = $this->pdo->query("SELECT COUNT(*) AS new_users FROM membres WHERE date_inscription >= CURDATE() - INTERVAL 1 MONTH");
        $result = $stmt->fetch();
        return $result['new_users'];
    }

    public function getOrdersStats($period)
    {
        switch ($period) {
            case "weekly":
                $query = "SELECT DATE(date_commande) AS date, COUNT(*) AS total 
                      FROM commandes 
                      WHERE date_commande >= CURDATE() - INTERVAL 7 DAY
                      GROUP BY DATE(date_commande) 
                      ORDER BY DATE(date_commande) ASC";
                break;
            case "monthly":
                $query = "SELECT DATE(date_commande) AS date, COUNT(*) AS total 
                      FROM commandes 
                      WHERE date_commande >= CURDATE() - INTERVAL 30 DAY
                      GROUP BY DATE(date_commande) 
                      ORDER BY DATE(date_commande) ASC";
                break;
            default:
                $query = "SELECT DATE(date_commande) AS date, COUNT(*) AS total 
                      FROM commandes 
                      WHERE DATE(date_commande) = CURDATE()
                      GROUP BY DATE(date_commande)
                      ORDER BY DATE(date_commande) ASC";
                break;
        }

        $stmt = $this->pdo->query($query);
        $data = $stmt->fetchAll(\PDO::FETCH_KEY_PAIR);

        return [
            "labels" => array_keys($data),
            "values" => array_values($data)
        ];
    }

    public function getRevenueStats($period)
    {
        switch ($period) {
            case "weekly":
                $query = "SELECT DATE(date_commande) AS date, ROUND(SUM(montant_ttc), 2) AS total 
                      FROM commandes 
                      WHERE date_commande >= CURDATE() - INTERVAL 7 DAY
                      GROUP BY DATE(date_commande) 
                      ORDER BY DATE(date_commande) ASC";
                break;
            case "monthly":
                $query = "SELECT DATE(date_commande) AS date, ROUND(SUM(montant_ttc), 2) AS total 
                      FROM commandes 
                      WHERE date_commande >= CURDATE() - INTERVAL 30 DAY
                      GROUP BY DATE(date_commande) 
                      ORDER BY DATE(date_commande) ASC";
                break;
            default:
                $query = "SELECT DATE(date_commande) AS date, ROUND(SUM(montant_ttc), 2) AS total 
                      FROM commandes 
                      WHERE DATE(date_commande) = CURDATE()
                      GROUP BY DATE(date_commande)
                      ORDER BY DATE(date_commande) ASC";
                break;
        }

        $stmt = $this->pdo->query($query);
        $data = $stmt->fetchAll(\PDO::FETCH_KEY_PAIR);

        return [
            "labels" => array_keys($data),
            "values" => array_values($data)
        ];
    }

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
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function addOrder($userId, $cartItems, $total)
    {
        $pdo = $this->getPdo();
        $stmt = $pdo->prepare("INSERT INTO commandes (id_membre, date_commande, montant_ttc, statut_preparation)
            VALUES (:userId, NOW(), :total, 'En attente')");
        $stmt->execute([
            'userId' => $userId,
            'total' => $total
        ]);

        $orderId = $pdo->lastInsertId();

        foreach ($cartItems as $item) {
            $stmt = $pdo->prepare("INSERT INTO contenir (id_commande, id_article, quantite)
                VALUES (:orderId, :productId, :quantity)");
            $stmt->execute([
                'orderId' => $orderId,
                'productId' => $item['id'],
                'quantity' => $item['quantite']
            ]);
        }

        return $orderId;
    }
}
