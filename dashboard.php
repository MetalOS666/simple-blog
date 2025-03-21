<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}
include 'db.php';

// Supprimer un article
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];

    // Récupérer l'image associée à l'article
    try {
        $stmt = $pdo->prepare("SELECT image FROM articles WHERE id = ?");
        $stmt->execute([$id]);
        $article = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($article && file_exists($article['image'])) {
            unlink($article['image']);
        }

        $stmt = $pdo->prepare("DELETE FROM articles WHERE id = ?");
        $stmt->execute([$id]);
        header('Location: dashboard.php');
        exit;
    } catch (PDOException $e) {
        $error = "Erreur lors de la suppression: " . $e->getMessage();
    }
}

// Récupérer les articles
try {
    $articles = $pdo->query("SELECT * FROM articles ORDER BY created_at DESC")->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error = "Erreur de récupération des articles: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Tableau de Bord</title>
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

        .nav-links {
            text-align: center;
            margin: 20px 0;
        }

        .nav-links a {
            margin: 0 10px;
            font-size: 1.1rem;
            color: #1e90ff;
        }

        .nav-links a:hover {
            text-decoration: underline;
        }

        .container {
            margin: 20px auto;
            max-width: 900px;
            padding: 20px;
        }

        article {
            background-color: #444444;
            margin-bottom: 20px;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
        }

        article img {
            max-width: 100%;
            height: auto;
            border-radius: 8px;
        }

        article h2 {
            margin-top: 0;
        }

        article p {
            font-size: 1rem;
            line-height: 1.8;
        }

        small {
            color: #888888;
        }

        button {
            background-color: #1e90ff;
            color: #fff;
            border: none;
            padding: 5px 10px;
            border-radius: 4px;
            cursor: pointer;
        }

        button:hover {
            background-color: #4682b4;
        }

        footer {
            background-color: #333333;
            color: #ffffff;
            text-align: center;
            padding: 10px;
            position: fixed;
            bottom: 0;
            width: 100%;
        }
    </style>
</head>
<body>
    <h1>Bienvenue, <?= htmlspecialchars($_SESSION['username']) ?></h1>
    <div class="nav-links">
        <a href="add.php">Ajouter un article</a> | <a href="logout.php">Déconnexion</a>
    </div>
    <div class="container">
        <?php if (isset($error)) echo "<p style='color:red;'>".htmlspecialchars($error)."</p>"; ?>

        <?php foreach ($articles as $article): ?>
            <article>
                <h2><?= htmlspecialchars($article['title']) ?></h2>
                <p><?= nl2br(htmlspecialchars($article['content'])) ?></p>
                <?php if ($article['image']): ?>
                    <img src="<?= htmlspecialchars($article['image']) ?>" alt="Image de l'article" width="200">
                <?php endif; ?>
                <small>Publié le <?= htmlspecialchars($article['created_at']) ?></small>
                <br>
                <a href="dashboard.php?delete=<?= $article['id'] ?>" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet article ?')">
                    <button>Supprimer</button>
                </a>
                <hr>
            </article>
        <?php endforeach; ?>
    </div>
    <footer>
        <p>&copy; 2025 Simple-Blog - Tous droits réservés</p>
    </footer>
</body>
</html>
