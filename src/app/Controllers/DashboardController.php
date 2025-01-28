<?php

namespace Controllers;

use Models\OrderModel;

class DashboardController
{
    public function index()
    {
        $orderModel = new OrderModel();
        $stats = $orderModel->getDailyOrders();
        include('src/app/Views/admin/dashboard.php');
    }
}
?>