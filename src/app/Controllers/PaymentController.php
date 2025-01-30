<?php
// PaymentController.php - Gestion du paiement avec Stripe
namespace Controllers;

require_once 'vendor/autoload.php';

use Stripe\Stripe;
use Stripe\Checkout\Session;

class PaymentController
{
    public function createSession()
    {
        session_start();
        
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['order_error'] = "Vous devez être connecté pour payer.";
            header('Location: login.php');
            exit();
        }

        $cart = $_SESSION['cart'] ?? [];
        if (empty($cart)) {
            $_SESSION['order_error'] = "Votre panier est vide.";
            header('Location: cart.php');
            exit();
        }

        Stripe::setApiKey('sk_test_...'); // Clé API secrète Stripe

        $lineItems = [];
        foreach ($cart as $item) {
            $lineItems[] = [
                'price_data' => [
                    'currency' => 'eur',
                    'product_data' => [
                        'name' => $item['name'],
                    ],
                    'unit_amount' => $item['price'] * 100, // Prix en centimes
                ],
                'quantity' => $item['quantity'],
            ];
        }

        $checkout_session = Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [$lineItems],
            'mode' => 'payment',
            'success_url' => 'http://localhost/success.php',
            'cancel_url' => 'http://localhost/cancel.php',
        ]);

        header("Location: " . $checkout_session->url);
        exit();
    }

    public function paymentSuccess()
    {
        session_start();
        $_SESSION['order_success'] = "Paiement validé ✅. Votre commande a été enregistrée.";
        header('Location: orders');
        exit();
    }

    public function paymentCancel()
    {
        session_start();
        $_SESSION['order_error'] = "Paiement annulé ❌. Veuillez réessayer.";
        header('Location: cart');
        exit();
    }
}