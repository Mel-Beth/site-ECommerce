<?php
include '../libs/fpdf186/fpdf.php';
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
    $commande = $stmt->fetchAll();

    if (!$commande) {
        die("Commande introuvable.");
    }
} catch (PDOException $e) {
    die("Erreur lors de la récupération de la commande : " . $e->getMessage());
}

// Création de la facture PDF
$pdf = new FPDF();
$pdf->AddPage();

// Ajout du logo
$pdf->Image('../assets/images/logo.jpeg', 10, 10, 40);

// Informations de l'entreprise
$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell(0, 10, 'Vide Ton Porte-Monnaie', 0, 1, 'C');
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(0, 10, 'Adresse : 123 Rue Imaginaire, 75000 Paris, France', 0, 1, 'C');
$pdf->Cell(0, 10, 'Téléphone : +33 1 23 45 67 89', 0, 1, 'C');
$pdf->Cell(0, 10, 'Email : contact@vide-porte-monnaie.com', 0, 1, 'C');
$pdf->Ln(10);

// Informations de la facture
$pdf->SetFont('Arial', 'B', 14);
$pdf->Cell(0, 10, 'Facture #'.$commandeId, 0, 1, 'C');
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(0, 10, 'Date de la commande : ' . $commande[0]['created_at'], 0, 1, 'C');
$pdf->Ln(10);

// Détails du client
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 10, 'Client : ' . $commande[0]['user_name'], 0, 1);
$pdf->Ln(10);

// Tableau des articles
$pdf->SetFont('Arial', 'B', 12);
$pdf->SetFillColor(200, 220, 255);
$pdf->Cell(90, 10, 'Article', 1, 0, 'C', true);
$pdf->Cell(30, 10, 'Prix Unitaire', 1, 0, 'C', true);
$pdf->Cell(30, 10, 'Quantité', 1, 0, 'C', true);
$pdf->Cell(40, 10, 'Total', 1, 1, 'C', true);

$pdf->SetFont('Arial', '', 12);
foreach ($commande as $item) {
    $pdf->Cell(90, 10, $item['article_name'], 1);
    $pdf->Cell(30, 10, number_format($item['article_price'], 2) . ' €', 1);
    $pdf->Cell(30, 10, $item['quantity'], 1);
    $pdf->Cell(40, 10, number_format($item['article_price'] * $item['quantity'], 2) . ' €', 1);
    $pdf->Ln();
}

// Calcul TVA
$tva_rate = 0.20; // TVA à 20%
$tva_amount = $commande[0]['total_price'] * $tva_rate;
$total_with_tva = $commande[0]['total_price'] + $tva_amount;

// Montant total
$pdf->Ln(10);
$pdf->SetFont('Arial', 'B', 14);
$pdf->Cell(0, 10, 'TVA (20%) : ' . number_format($tva_amount, 2) . ' €', 0, 1, 'R');
$pdf->Cell(0, 10, 'Total TTC : ' . number_format($total_with_tva, 2) . ' €', 0, 1, 'R');
$pdf->Ln(10);

// Conditions de paiement et mentions légales
$pdf->SetFont('Arial', 'I', 10);
$pdf->Cell(0, 10, 'Conditions de paiement : Paiement dû dans les 30 jours suivant la date de facturation.', 0, 1);
$pdf->Cell(0, 10, 'Numéro SIRET : 123 456 789 00012', 0, 1);
$pdf->Cell(0, 10, 'Conditions générales de vente : https://www.vide-porte-monnaie.com/cgv', 0, 1);

// Générer le fichier PDF
$pdf->Output('I', 'Facture-'.$commandeId.'.pdf');
