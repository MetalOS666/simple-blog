// logout.php - Déconnexion
<?php
session_start();

// Supprimer toutes les variables de session
$_SESSION = [];

// Détruire la session
session_destroy();

// Redirection avec un message de confirmation
header('Location: login.php?logout=1');
exit;
?>