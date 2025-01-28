<?php

namespace Controllers;

use Models\OrderModel;

class OrderController
{
    public function index()
    {
        $orderModel = new OrderModel();
        $orders = $orderModel->getAllOrders();
        include('src/app/Views/admin/orders.php');
    }

    public function show($orderId)
    {
        $orderModel = new OrderModel();
        $order = $orderModel->getOrderById($orderId);
        include('src/app/Views/admin/order_details.php');
    }

    public function updateStatus($orderId, $status)
    {
        $orderModel = new OrderModel();
        $orderModel->updateOrderStatus($orderId, $status);
        header('Location: admin/orders');
        exit();
    }
}
?>