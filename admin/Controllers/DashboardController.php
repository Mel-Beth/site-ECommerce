<?php

namespace Admin\Controllers;

use Admin\Models\OrderModel;
use Admin\Models\ProductModel;

class DashboardController
{
    public function index()
    {
        // Récupérer les données nécessaires pour le tableau de bord
        $orderModel = new OrderModel();
        $productModel = new ProductModel();

        $stats = $orderModel->getDailyOrders();
        $categories = $productModel->getOrdersByCategory();

        // Charger la vue
        require __DIR__ . '/../Views/dashboard.php';
    }
}
