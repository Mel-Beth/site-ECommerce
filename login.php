<?php
include 'includes/init.php'; // Inclure le fichier d'initialisation

// Vérifier si l'utilisateur est déjà connecté
if (isset($_SESSION['user'])) {
    // Si l'utilisateur est connecté, rediriger vers son compte (éviter boucle si déjà sur login.php)
    if (basename($_SERVER['PHP_SELF']) !== 'login.php') {
        header('Location: user.php');
        exit();
    }
}

// Traitement de la connexion
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if (!empty($email) && !empty($password)) {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = "Format d'email invalide.";
        } else {
            try {
                $stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE email = :email AND etat = TRUE");
                $stmt->execute(['email' => $email]);

                if ($stmt->rowCount() > 0) {
                    $user = $stmt->fetch(PDO::FETCH_ASSOC);
                } else {
                    $error = "Aucun utilisateur trouvé avec cet email ou l'email est désactivé.";
                }

                if (isset($user)) {
                    if (password_verify($password, $user['password'])) {
                        $_SESSION['user'] = [
                            'user_id' => $user['user_id'],
                            'nom' => $user['nom'],
                            'prenom' => $user['prenom'],
                            'email' => $user['email'],
                            'role' => $user['role'],
                        ];

                        $redirect = ($user['role'] === 'admin') ? 'admin/dashboard.php' : 'index.php';
                        header("Location: $redirect");
                        exit();
                    } else {
                        $error = "Mot de passe incorrect.";
                    }
                }
            } catch (PDOException $e) {
                $error = "Erreur lors de la connexion à la base de données : " . $e->getMessage();
            }
        }
    } else {
        $error = "Les champs email et mot de passe sont obligatoires.";
    }
}

include 'includes/head.php';
include 'includes/header.php';
include 'includes/sidebar.php';
?>


<main class="flex items-center justify-center h-screen bg-gray-100">
    <div class="bg-white p-8 rounded-lg shadow-lg w-96">
        <h1 class="text-2xl font-bold mb-4"><?= $t['login_title'] ?></h1>
        <?php if ($error): ?>
            <p class="text-red-500 mb-4"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>
        <form method="post" action="">
            <label for="email" class="block text-gray-700"><?= $t['email'] ?>:</label>
            <input type="email" id="email" name="email" class="w-full p-2 border rounded mb-4" required>
            <label for="password" class="block text-gray-700"><?= $t['password'] ?>:</label>
            <input type="password" id="password" name="password" class="w-full p-2 border rounded mb-4" required>
            <button type="submit" class="bg-yellow-500 text-white px-4 py-2 rounded hover:bg-yellow-600 w-full"><?= $t['login_button'] ?></button>
        </form>
    </div>
</main>

<?php include 'includes/footer.php'; ?>
