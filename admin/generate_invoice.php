<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: /index.php');
    exit();
}

require_once '../lib/fpdf186/fpdf.php';
include '../php/db.php'; // Connexion à la BDD

// Vérification de l'ID de la commande
$commandeId = $_GET['id'] ?? 0;

if (!$commandeId) {
    die("ID de commande invalide.");
}

// Récupérer les détails de la commande
try {
    $stmt = $pdo->prepare("
        SELECT c.id, c.user_name, c.total_price, c.created_at, c.updated_at, cd.quantity, a.name AS article_name, a.price AS article_price
        FROM commandes c
        LEFT JOIN commande_details cd ON c.id = cd.commande_id
        LEFT JOIN articles a ON cd.article_id = a.id
        WHERE c.id = :id
    ");
    $stmt->execute(['id' => $commandeId]);
    $commande = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (!$commande) {
        die("Commande introuvable.");
    }
} catch (PDOException $e) {
    die("Erreur lors de la récupération de la commande : " . $e->getMessage());
}

// Création de la facture PDF
$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 16);

// En-tête de la facture
$pdf->Cell(0, 10, 'Facture #'.$commandeId, 0, 1, 'C');
$pdf->Ln(10);

$pdf->SetFont('Arial', '', 12);
$pdf->Cell(0, 10, 'Client : ' . $commande[0]['user_name'], 0, 1);
$pdf->Cell(0, 10, 'Date : ' . $commande[0]['created_at'], 0, 1);
$pdf->Ln(10);

// Tableau des articles
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(90, 10, 'Article', 1);
$pdf->Cell(30, 10, 'Prix', 1);
$pdf->Cell(30, 10, 'Quantite', 1);
$pdf->Cell(40, 10, 'Total', 1);
$pdf->Ln();

$pdf->SetFont('Arial', '', 12);
foreach ($commande as $item) {
    $pdf->Cell(90, 10, utf8_decode($item['article_name']), 1);
    $pdf->Cell(30, 10, number_format($item['article_price'], 2) . ' €', 1);
    $pdf->Cell(30, 10, $item['quantity'], 1);
    $pdf->Cell(40, 10, number_format($item['article_price'] * $item['quantity'], 2) . ' €', 1);
    $pdf->Ln();
}

// Total de la commande
$pdf->Ln(10);
$pdf->SetFont('Arial', 'B', 14);
$pdf->Cell(0, 10, 'Total : ' . number_format($commande[0]['total_price'], 2) . ' €', 0, 1, 'R');

// Générer le fichier PDF
$pdf->Output('I', 'Facture-'.$commandeId.'.pdf');
