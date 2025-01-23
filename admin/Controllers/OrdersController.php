<?php
namespace Admin\Controllers;

use Admin\Models\OrderModel;

class OrdersController
{
    public function index()
    {
        $orderModel = new OrderModel();
        $orders = $orderModel->getAllOrders();
        require __DIR__ . '/../Views/orders.php';
    }

    public function updateStatus($orderId, $status)
    {
        $orderModel = new OrderModel();
        $orderModel->updateOrderStatus($orderId, $status);
        header('Location: /admin/orders');
        exit();
    }
}