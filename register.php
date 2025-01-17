<?php
include 'includes/init.php'; // Inclure le fichier d'initialisation

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    if (!empty($name) && !empty($email) && !empty($password) && !empty($confirm_password)) {
        if ($password === $confirm_password) {
            try {
                $stmt = $pdo->prepare("SELECT id FROM utilisateurs WHERE email = :email");
                $stmt->execute(['email' => $email]);
                if ($stmt->fetch()) {
                    $error = $t['email_already_used'];
                } else {
                    $hashed_password = password_hash($password, PASSWORD_BCRYPT);
                    $stmt = $pdo->prepare("INSERT INTO utilisateurs (nom, email, password, role) VALUES (:name, :email, :password, 'client')");
                    $stmt->execute([
                        'name' => $name,
                        'email' => $email,
                        'password' => $hashed_password
                    ]);
                    $success = $t['account_created_success'];
                }
            } catch (PDOException $e) {
                $error = $t['registration_error'] . ": " . $e->getMessage();
            }
        } else {
            $error = $t['passwords_do_not_match'];
        }
    } else {
        $error = $t['fill_all_fields'];
    }
}

include 'includes/head.php';
include 'includes/header.php';
include 'includes/sidebar.php';
?>



<main class="flex items-center justify-center h-screen bg-gray-100">
    <div class="bg-white p-8 rounded-lg shadow-lg w-96">
        <h1 class="text-2xl font-bold mb-4"><?= $t['register'] ?></h1>
        <?php if ($error): ?>
            <p class="text-red-500 mb-4"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>
        <?php if ($success): ?>
            <p class="text-green-500 mb-4"><?= htmlspecialchars($success) ?></p>
        <?php endif; ?>
        <form method="post" action="">
            <label for="name" class="block text-gray-700"><?= $t['name'] ?>:</label>
            <input type="text" id="name" name="name" class="w-full p-2 border rounded mb-4" required>
            <label for="email" class="block text-gray-700"><?= $t['email'] ?>:</label>
            <input type="email" id="email" name="email" class="w-full p-2 border rounded mb-4" required>
            <label for="password" class="block text-gray-700"><?= $t['password'] ?>:</label>
            <input type="password" id="password" name="password" class="w-full p-2 border rounded mb-4" required>
            <label for="confirm_password" class="block text-gray-700"><?= $t['confirm_password'] ?>:</label>
            <input type="password" id="confirm_password" name="confirm_password" class="w-full p-2 border rounded mb-4" required>
            <button type="submit" class="bg-yellow-500 text-white px-4 py-2 rounded hover:bg-yellow-600 w-full"><?= $t['register_button'] ?></button>
        </form>
    </div>
</main>

<?php include 'includes/footer.php'; ?>
