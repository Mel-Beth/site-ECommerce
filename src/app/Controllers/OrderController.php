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
        // Récupérer les informations de la commande
        $order = $orderModel->getOrderById($orderId);
        // Récupérer les articles associés à la commande
        $orderItems = $orderModel->getOrderItems($orderId);
        // Vérifier si la commande existe
        if (!$order) {
            throw new \Exception("Commande introuvable.");
        }

        // Créer un PDF avec TCPDF
        $pdf = new TCPDF();
        $pdf->AddPage();

        // Ajouter le logo
        $pdf->Image('./assets/images.logo.jpeg', 10, 10, 40); // Remplacez le chemin de l'image par le bon chemin du logo

        // Informations de l'entreprise
        $pdf->SetFont('helvetica', 'B', 16);
        $pdf->Cell(0, 10, 'Vide Ton Porte-Monnaie', 0, 1, 'C');
        $pdf->SetFont('helvetica', '', 12);
        $pdf->Cell(0, 10, 'Adresse : 123 Rue Imaginaire, 75000 Paris, France', 0, 1, 'C');
        $pdf->Cell(0, 10, 'Téléphone : +33 1 23 45 67 89', 0, 1, 'C');
        $pdf->Cell(0, 10, 'Email : contact@vide-porte-monnaie.com', 0, 1, 'C');
        $pdf->Ln(10);

        // Informations de la facture
        $pdf->SetFont('helvetica', 'B', 14);
        $pdf->Cell(0, 10, 'Facture #' . $order['id_commande'], 0, 1, 'C');
        $pdf->SetFont('helvetica', '', 12);
        $pdf->Cell(0, 10, 'Date de la commande : ' . $order['date_commande'], 0, 1, 'C');
        $pdf->Ln(10);

        // Détails du client
        $pdf->SetFont('helvetica', 'B', 12);
        $pdf->Cell(0, 10, 'Client : ' . $order['pseudo_membre'], 0, 1);
        $pdf->Ln(10);

        // Tableau des articles
        $pdf->SetFont('helvetica', 'B', 12);
        $pdf->SetFillColor(200, 220, 255);
        $pdf->Cell(90, 10, 'Article', 1, 0, 'C', true);
        $pdf->Cell(30, 10, 'Prix Unitaire', 1, 0, 'C', true);
        $pdf->Cell(30, 10, 'Quantité', 1, 0, 'C', true);
        $pdf->Cell(40, 10, 'Total', 1, 1, 'C', true);

        $pdf->SetFont('helvetica', '', 12);
        $total = 0; // Variable pour calculer le total de la commande
        foreach ($orderItems as $item) {
            $lineTotal = $item['prix'] * $item['quantite']; // Calcul du total pour cet article
            $pdf->Cell(90, 10, $item['lib_article'], 1);
            $pdf->Cell(30, 10, number_format($item['prix'], 2) . ' €', 1);
            $pdf->Cell(30, 10, $item['quantite'], 1);
            $pdf->Cell(40, 10, number_format($lineTotal, 2) . ' €', 1);
            $pdf->Ln();
            $total += $lineTotal; // Ajouter au total global
        }

        // Calcul de la TVA
        $tva_rate = 0.20; // TVA à 20%
        $tva_amount = $total * $tva_rate;
        $total_with_tva = $total + $tva_amount;

        // Montant total
        $pdf->Ln(10);
        $pdf->SetFont('helvetica', 'B', 14);
        $pdf->Cell(0, 10, 'TVA (20%) : ' . number_format($tva_amount, 2) . ' €', 0, 1, 'R');
        $pdf->Cell(0, 10, 'Total TTC : ' . number_format($total_with_tva, 2) . ' €', 0, 1, 'R');
        $pdf->Ln(10);

        // Conditions de paiement et mentions légales
        $pdf->SetFont('helvetica', 'I', 10);
        $pdf->Cell(0, 10, 'Conditions de paiement : Paiement dû dans les 30 jours suivant la date de facturation.', 0, 1);
        $pdf->Cell(0, 10, 'Numéro SIRET : 123 456 789 00012', 0, 1);
        $pdf->Cell(0, 10, 'Conditions générales de vente : https://www.vide-porte-monnaie.com/cgv', 0, 1);

        // Générer et afficher le fichier PDF
        $pdf->Output('Facture-' . $order['id_commande'] . '.pdf', 'I');  // Téléchargement du PDF
    }
}
?>