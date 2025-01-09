<?php
session_start();
include 'php/db.php'; // Connexion à la base de données

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    // Vérification des champs
    if (!empty($email) && !empty($password)) {
        try {
            $stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE email = :email AND etat = TRUE");
            $stmt->execute(['email' => $email]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user && password_verify($password, $user['password'])) {
                // Stocker les informations dans la session
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['nom'];
                $_SESSION['role'] = $user['role'];

                // Rediriger vers le tableau de bord ou la page d'accueil
                $redirect = ($user['role'] === 'admin') ? '/admin/dashboard.php' : '/index.php';
                header("Location: $redirect");
                exit();
            } else {
                $error = "Email ou mot de passe incorrect.";
            }
        } catch (PDOException $e) {
            $error = "Erreur de connexion : " . $e->getMessage();
        }
    } else {
        $error = "Veuillez remplir tous les champs.";
    }
}
?>

<?php include 'includes/header.php'; ?>

<main class="flex items-center justify-center h-screen bg-gray-100">
    <div class="bg-white p-8 rounded-lg shadow-lg w-96">
        <h1 class="text-2xl font-bold mb-4">Connexion</h1>
        <?php if ($error): ?>
            <p class="text-red-500 mb-4"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>
        <form method="post" action="">
            <label for="email" class="block text-gray-700">Email :</label>
            <input type="email" id="email" name="email" class="w-full p-2 border rounded mb-4" required>
            <label for="password" class="block text-gray-700">Mot de passe :</label>
            <input type="password" id="password" name="password" class="w-full p-2 border rounded mb-4" required>
            <button type="submit" class="bg-yellow-500 text-white px-4 py-2 rounded hover:bg-yellow-600 w-full">Se connecter</button>
        </form>
    </div>
</main>

<?php include 'includes/footer.php'; ?>
