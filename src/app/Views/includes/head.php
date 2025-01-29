<?php
    $pageTitle = isset($admin) && $admin ? "Dashboard Admin" : "Vide Ton Porte-Monnaie";
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Site e-commerce moderne - Vide Ton Porte-Monnaie">
    <meta name="keywords" content="e-commerce, shopping, produits, achats en ligne">
    <meta name="author" content="Vide Ton Porte-Monnaie">
    <title><?php echo $pageTitle; ?></title>

    <!-- Styles -->
    <link rel="stylesheet" href="src/css/tailwindcssOutput.css"> <!-- Pour le site -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css"> <!-- Pour l'admin -->
    <link rel="stylesheet" href="src/css/style.css">
    <link rel="icon" href="assets/images/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">

    <!-- Scripts -->
    <script type="module" src="node_modules/chart.js/dist/chart.js"></script> <!-- Pour le site -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> <!-- Pour l'admin -->
</head>