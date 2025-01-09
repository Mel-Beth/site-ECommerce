<?php
include 'php/db.php'; // Connexion à la base de données
$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    // Vérification des champs
    if (!empty($name) && !empty($email) && !empty($password) && !empty($confirm_password)) {
        if ($password === $confirm_password) {
            try {
                // Vérifier si l'email existe déjà
                $stmt = $pdo->prepare("SELECT id FROM utilisateurs WHERE email = :email");
                $stmt->execute(['email' => $email]);
                if ($stmt->fetch()) {
                    $error = "Cet email est déjà utilisé.";
                } else {
                    // Insérer le nouvel utilisateur
                    $hashed_password = password_hash($password, PASSWORD_BCRYPT);
                    $stmt = $pdo->prepare("INSERT INTO utilisateurs (nom, email, password, role) VALUES (:name, :email, :password, 'client')");
                    $stmt->execute([
                        'name' => $name,
                        'email' => $email,
                        'password' => $hashed_password
                    ]);
                    $success = "Compte créé avec succès. Vous pouvez maintenant vous connecter.";
                }
            } catch (PDOException $e) {
                $error = "Erreur lors de l'inscription : " . $e->getMessage();
            }
        } else {
            $error = "Les mots de passe ne correspondent pas.";
        }
    } else {
        $error = "Veuillez remplir tous les champs.";
    }
}
?>

<?php include 'includes/header.php'; ?>

<main class="flex items-center justify-center h-screen bg-gray-100">
    <div class="bg-white p-8 rounded-lg shadow-lg w-96">
        <h1 class="text-2xl font-bold mb-4">Inscription</h1>
        <?php if ($error): ?>
            <p class="text-red-500 mb-4"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>
        <?php if ($success): ?>
            <p class="text-green-500 mb-4"><?= htmlspecialchars($success) ?></p>
        <?php endif; ?>
        <form method="post" action="">
            <label for="name" class="block text-gray-700">Nom :</label>
            <input type="text" id="name" name="name" class="w-full p-2 border rounded mb-4" required>

            <label for="email" class="block text-gray-700">Email :</label>
            <input type="email" id="email" name="email" class="w-full p-2 border rounded mb-4" required>

            <label for="password" class="block text-gray-700">Mot de passe :</label>
            <input type="password" id="password" name="password" class="w-full p-2 border rounded mb-4" required>

            <label for="confirm_password" class="block text-gray-700">Confirmer le mot de passe :</label>
            <input type="password" id="confirm_password" name="confirm_password" class="w-full p-2 border rounded mb-4" required>

            <button type="submit" class="bg-yellow-500 text-white px-4 py-2 rounded hover:bg-yellow-600 w-full">
                S'inscrire
            </button>
        </form>
    </div>
</main>

<?php include 'includes/footer.php'; ?>
