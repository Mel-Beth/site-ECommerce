<?php

namespace Controllers;

use Models\OrderModel;
use TCPDF; 

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

    public function generateInvoice($orderId)
    {
        $orderModel = new OrderModel();
        $order = $orderModel->getOrderById($orderId);
        $orderItems = $orderModel->getOrderItems($orderId); // Récupère les articles associés à la commande

        if (!$order) {
            throw new \Exception("Commande introuvable.");
        }

        // Crée un PDF avec TCPDF
        $pdf = new TCPDF();
        $pdf->AddPage();

        // Ajouter le titre de la facture
        $pdf->SetFont('helvetica', 'B', 16);
        $pdf->Cell(0, 10, "Facture - Commande #{$order['id_commande']}", 0, 1, 'C');

        // Informations de la commande
        $pdf->SetFont('helvetica', '', 12);
        $pdf->Cell(100, 10, "Date de commande: {$order['date_commande']}", 0, 1);
        $pdf->Cell(100, 10, "Statut: " . ($order['statut_preparation'] ? 'Préparation' : 'En attente'), 0, 1);

        // Ajouter les articles de la commande
        $pdf->Ln(10);
        $pdf->Cell(30, 10, 'Article', 1, 0, 'C');
        $pdf->Cell(30, 10, 'Quantité', 1, 0, 'C');
        $pdf->Cell(30, 10, 'Prix Unitaire', 1, 0, 'C');
        $pdf->Cell(30, 10, 'Total', 1, 1, 'C');

        foreach ($orderItems as $item) {
            $pdf->Cell(30, 10, $item['lib_article'], 1);
            $pdf->Cell(30, 10, $item['quantite'], 1, 0, 'C');
            $pdf->Cell(30, 10, number_format($item['prix'], 2) . ' €', 1, 0, 'C');
            $pdf->Cell(30, 10, number_format($item['quantite'] * $item['prix'], 2) . ' €', 1, 1, 'C');
        }

        // Montant total
        $total = array_sum(array_map(function ($item) {
            return $item['quantite'] * $item['prix'];
        }, $orderItems));

        $pdf->Ln(10);
        $pdf->Cell(90, 10, 'Montant Total', 1);
        $pdf->Cell(30, 10, number_format($total, 2) . ' €', 1, 1, 'C');

        // Envoi de la facture au navigateur
        $pdf->Output("facture_{$order['id_commande']}.pdf", 'I'); // Affiche la facture dans le navigateur
    }
}

?>