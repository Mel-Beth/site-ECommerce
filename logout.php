<?php
session_start();
session_unset(); // Supprime toutes les variables de session
session_destroy(); // Détruit la session
header("Location: index"); // Redirige vers la page de connexion
exit();
?>
