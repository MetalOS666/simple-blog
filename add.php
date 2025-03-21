<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $content = $_POST['content'];
    $image = $_FILES['image'];

    if (!empty($title) && !empty($content)) {
        $imagePath = null;
        if ($image['error'] === 0) {
            if ($image['size'] > 2 * 1024 * 1024) {
                $error = "L'image ne doit pas dépasser 2 Mo.";
            }
            $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
            if (in_array($image['type'], $allowedTypes)) {
                $imagePath = 'uploads/' . basename($image['name']);
                if (!is_dir('uploads')) {
                    mkdir('uploads', 0777, true);
                }
                move_uploaded_file($image['tmp_name'], $imagePath);
            } else {
                $error = "Le fichier téléchargé n'est pas une image valide.";
            }
        }

        if (!isset($error)) {
            try {
                $stmt = $pdo->prepare("INSERT INTO articles (title, content, image) VALUES (?, ?, ?)");
                $stmt->execute([$title, $content, $imagePath]);
                header('Location: dashboard.php');
                exit;
            } catch (PDOException $e) {
                $error = "Erreur de base de données: " . $e->getMessage();
            }
        }
    } else {
        $error = "Tous les champs sont obligatoires.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ajouter un article</title>
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
            max-width: 600px;
            margin: 20px auto;
        }

        input, textarea, button {
            background-color: #333333;
            color: #f0f0f0;
            border: 1px solid #555555;
            border-radius: 4px;
            padding: 10px;
            width: 100%;
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
    <h1>Ajouter un nouvel article</h1>
    <?php if (isset($error)) echo "<p>$error</p>"; ?>
    <form method="post" enctype="multipart/form-data">
        <label for="title">Titre :</label>
        <input type="text" id="title" name="title" required>
        <br>
        <label for="content">Contenu :</label>
        <textarea id="content" name="content" required></textarea>
        <br>
        <label for="image">Image :</label>
        <input type="file" id="image" name="image" accept="image/jpeg, image/png, image/gif">
        <br>
        <button type="submit">Publier</button>
    </form>
    <br>
    <a href="dashboard.php">Retour</a>
</body>
</html>
