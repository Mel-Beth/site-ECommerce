<?php

namespace Controllers;

use Models\OrderModel;

class DashboardController
{
    public function index()
    {
        $orderModel = new OrderModel();

        // Récupération des données nécessaires pour les graphiques et les chiffres clés
        $dailyOrdersStats = $orderModel->getDailyOrders();
        $monthlyRevenueStats = $orderModel->getMonthlyRevenue();  // Utilisation de la méthode mise à jour
        $categoryStats = $orderModel->getCategoryStats();
        $pendingOrdersCount = $orderModel->getPendingOrdersCount();
        $totalRevenue = $orderModel->getTotalRevenue();
        $lowStockProducts = $orderModel->getLowStockProducts();  // Stocks bas
        $totalStock = $orderModel->getTotalStock();  // Nombre total d'articles en stock
        $avgRevenuePerOrder = $orderModel->getAverageRevenuePerOrder();  // Revenu moyen par commande
        $newUsersCount = $orderModel->getNewUsersCount();  // Nouveaux utilisateurs inscrits
        $topSellingProducts = $orderModel->getTopSellingProducts();  // Top 5 des produits les plus vendus

        // Récupérer le total des commandes
        $totalOrders = $orderModel->getTotalOrders();

        // Passez toutes les données à la vue
        include('src/app/Views/admin/dashboard.php');
    }
}
