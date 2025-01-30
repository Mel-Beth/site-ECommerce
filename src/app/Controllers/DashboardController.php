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
        $totalOrders = $orderModel->getTotalOrders();

        // Passez toutes les données à la vue
        include('src/app/Views/admin/dashboard.php');
    }

    public function getNotifications()
    {
        $orderModel = new OrderModel();

        $notifications = [
            'pending_orders' => $orderModel->getPendingOrdersCount(),
            'low_stock' => count($orderModel->getLowStockProducts())
        ];

        echo json_encode($notifications);
        exit();
    }

    public function stats($period = 'daily')
{
    $orderModel = new OrderModel();

    $orders = $orderModel->getOrdersStats($period);
    $revenues = $orderModel->getRevenueStats($period);
    $topProducts = $orderModel->getTopSellingProducts();

    echo json_encode([
        "orders" => ["labels" => array_keys($orders), "values" => array_values($orders)],
        "revenues" => ["labels" => array_keys($revenues), "values" => array_values($revenues)],
        "topProducts" => $topProducts
    ]);
    exit();
}

}
