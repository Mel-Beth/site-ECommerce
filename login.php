<?php
include 'php/db.php'; // Connexion à la base de données

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    // Vérification des champs
    if (!empty($email) && !empty($password)) {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = "invalid_email_format";
        } else {
            try {
                $stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE email = :email AND etat = TRUE");
                $stmt->execute(['email' => $email]);
                $user = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($user && password_verify($password, $user['password'])) {
                    session_start();
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['user_name'] = $user['nom'];
                    $_SESSION['role'] = $user['role'];

                    $redirect = ($user['role'] === 'admin') ? 'admin/dashboard.php' : 'index.php';
                    header("Location: $redirect");
                    exit();
                } else {
                    $error = "login_error";
                }
            } catch (PDOException $e) {
                $error = "connection_error";
            }
        }
    } else {
        $error = "fill_all_fields";
    }
}

include 'includes/head.php';
include 'includes/header.php';
include 'includes/sidebar.php';

$translations = include 'includes/translations.php';
$lang = $_SESSION['lang'] ?? 'fr';
$t = $translations[$lang];
?>

<main class="flex items-center justify-center h-screen bg-gray-100">
    <div class="bg-white p-8 rounded-lg shadow-lg w-96">
        <h1 class="text-2xl font-bold mb-4"><?= $t['login_title'] ?></h1>
        <?php if ($error): ?>
            <p class="text-red-500 mb-4"><?= htmlspecialchars($t[$error] ?? '') ?></p>
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
