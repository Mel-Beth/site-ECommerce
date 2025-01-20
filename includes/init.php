<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start(); // Démarre la session si elle n'est pas déjà active
}

// Connexion à la base de données
include 'php/db.php';

// Charger les traductions
$translations = include 'includes/translations.php';
$lang = $_SESSION['lang'] ?? 'fr'; // Langue par défaut : français
$t = $translations[$lang]; // Charger les traductions pour la langue actuelle

// Gérer le changement de langue
if (isset($_GET['lang'])) {
    $newLang = htmlspecialchars($_GET['lang']); // Sécuriser l'entrée
    if (array_key_exists($newLang, $translations)) { // Vérifier si la langue est supportée
        $_SESSION['lang'] = $newLang;
    }
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit();
}

// Vérifier si l'utilisateur est déjà connecté
if (isset($_SESSION['user'])) {
    // Récupérer les informations de l'utilisateur
    $userName = $_SESSION['user']['nom'] ?? '';
    $userPrenom = $_SESSION['user']['prenom'] ?? '';
} else {
    $userName = '';
    $userPrenom = '';
}

// Traitement de la connexion si un formulaire POST est envoyé
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['email'], $_POST['password'])) {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $error = '';

    if (!empty($email) && !empty($password)) {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = "Format d'email invalide.";
        } else {
            try {
                $stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE email = :email AND etat = TRUE");
                $stmt->execute(['email' => $email]);

                if ($stmt->rowCount() > 0) {
                    $user = $stmt->fetch(PDO::FETCH_ASSOC);

                    if (password_verify($password, $user['password'])) {
                        // Vérification du rôle utilisateur
                        $role = $user['role'];

                        if ($role === 'admin') {
                            // Rediriger vers l'interface administrateur
                            $_SESSION['user'] = [
                                'user_id' => $user['user_id'],
                                'nom' => $user['nom'],
                                'prenom' => $user['prenom'],
                                'email' => $user['email'],
                                'role' => $role,
                            ];
                            header('Location: admin/dashboard.php');
                            exit();
                        } elseif ($role === 'client') {
                            // Rediriger vers l'interface utilisateur
                            $_SESSION['user'] = [
                                'user_id' => $user['user_id'],
                                'nom' => $user['nom'],
                                'prenom' => $user['prenom'],
                                'email' => $user['email'],
                                'role' => $role,
                            ];
                            header('Location: index.php');
                            exit();
                        } else {
                            $error = "Rôle utilisateur non reconnu.";
                        }
                    } else {
                        $error = "Mot de passe incorrect.";
                    }
                } else {
                    $error = "Aucun utilisateur trouvé avec cet email ou l'email est désactivé.";
                }
            } catch (PDOException $e) {
                $error = "Erreur lors de la connexion à la base de données : " . $e->getMessage();
            }
        }
    } else {
        $error = "Les champs email et mot de passe sont obligatoires.";
    }

    // Si une erreur survient, elle peut être stockée dans la session pour affichage ultérieur
    if (!empty($error)) {
        $_SESSION['login_error'] = $error;
        header('Location: login.php');
        exit();
    }
}

// Initialisation du panier
$cartItems = $_SESSION['cart'] ?? [];
if (!is_array($cartItems)) {
    $cartItems = []; // Garantir un tableau vide si nécessaire
}
?>
