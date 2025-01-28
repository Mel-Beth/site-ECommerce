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

    public function updateStatus($orderId, $status)
    {
        $orderModel = new OrderModel();
        if (in_array($status, [0, 1])) {
            $orderModel->updateOrderStatus($orderId, $status);
        }
        header('Location: admin/orders');
        exit();
    }

    public function show($orderId)
    {
        $orderModel = new OrderModel();
        $order = $orderModel->getOrderById($orderId);

        if (!$order) {
            $_SESSION['error'] = "Commande non trouvée.";
            header('Location: admin/orders');
            exit();
        }

        // Récupérer les articles de la commande
        $orderItems = $orderModel->getOrderItems($orderId);

        include('src/app/Views/admin/order_details.php');
    }
}