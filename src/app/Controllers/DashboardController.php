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

        $stats = $orderModel->getDailyOrders();
        $categories = $productModel->getOrdersByCategory();

        include("src/app/Views/admin/dashboard.php"); // Mettez à jour le chemin de la vue
    }
}