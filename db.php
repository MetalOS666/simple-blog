<?php
$host = 'Adresse serveur base de données';
$dbname = 'Nom base de données';
$username = 'Nom utilisateur';
$password = 'Mot de passe';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Création de la table des utilisateurs si elle n'existe pas
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS users (
            id INT AUTO_INCREMENT PRIMARY KEY,
            username VARCHAR(255) NOT NULL UNIQUE,
            password VARCHAR(255) NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )
    ");

    // Création de la table des articles si elle n'existe pas
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS articles (
            id INT AUTO_INCREMENT PRIMARY KEY,
            title VARCHAR(255) NOT NULL,
            content TEXT NOT NULL,
            image VARCHAR(255) DEFAULT NULL, 
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )
    ");

    // Ajout d'un utilisateur administrateur par défaut (si aucun utilisateur n'existe)
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM users");
    $stmt->execute();
    $userCount = $stmt->fetchColumn();

    if ($userCount == 0) {
        // Créer un utilisateur administrateur
        $passwordHash = password_hash('admin', PASSWORD_BCRYPT); // Mot de passe sécurisé
        $stmt = $pdo->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
        $stmt->execute(['admin', $passwordHash]);
    }

} catch (PDOException $e) {
    die("Erreur de connexion: " . $e->getMessage());
}
?>

