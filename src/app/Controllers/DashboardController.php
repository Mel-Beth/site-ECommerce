<?php

namespace Controllers;

use Models\OrderModel;
use Models\ProductModel;

class DashboardController
{
    public function index()
    {
        $orderModel = new OrderModel();
        $productModel = new ProductModel();

        try {
            $stats = $orderModel->getDailyOrders();
            $categories = $productModel->getOrdersByCategory();

            // Ajout de statistiques supplémentaires
            $totalRevenue = $orderModel->getTotalRevenue();
            $pendingOrders = $orderModel->getPendingOrdersCount();

            include("src/app/Views/admin/dashboard.php");
        } catch (\Exception $e) {
            die($e->getMessage());
        }
    }
}