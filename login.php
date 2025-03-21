<?php
session_start();
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        header('Location: dashboard.php');
        exit;
    } else {
        $error = "Identifiants incorrects";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #2e2e2e;
            color: #f0f0f0;
            margin: 0;
            padding: 0;
            line-height: 1.6;
        }

        h1 {
            color: #ffffff;
            font-family: 'Helvetica Neue', sans-serif;
            text-align: center;
            padding: 20px;
        }

        form {
            background-color: #444444;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
            max-width: 400px;
            margin: 20px auto;
        }

        input, button {
            background-color: #333333;
            color: #f0f0f0;
            border: 1px solid #555555;
            border-radius: 4px;
            padding: 10px;
            width: 95%;
            margin-bottom: 10px;
        }

        button {
            background-color: #1e90ff;
            border: none;
            cursor: pointer;
        }

        button:hover {
            background-color: #4682b4;
        }

        p {
            color: red;
            text-align: center;
        }
    </style>
</head>
<body>
    <h1>Connexion Administrateur</h1>
    <?php if (isset($error)) echo "<p>$error</p>"; ?>
    <form method="post">
        <label for="username">Nom d'utilisateur :</label>
        <input type="text" id="username" name="username" required>
        <br>
        <label for="password">Mot de passe :</label>
        <input type="password" id="password" name="password" required>
        <br>
        <button type="submit">Se connecter</button>
    </form>
</body>
</html>
