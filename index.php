<?php
include 'db.php';

// Récupérer les articles
$articles = $pdo->query("SELECT * FROM articles ORDER BY created_at DESC")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blog</title>
    <link href="https://fonts.googleapis.com/css2?family=Ubuntu:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Ubuntu', sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            color: #333;
        }
        h1 {
            text-align: center;
            color: #fff; /* Couleur blanche pour le titre */
            padding: 20px;
            background-color: #4f4f4f; /* Fond gris foncé pour le titre */
            margin: 0;
            font-size: 2em;
        }
        .container {
            width: 80%;
            margin: 20px auto;
            background-color: #fff;
            padding: 20px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }
        .post {
            margin-bottom: 30px; /* Séparation entre chaque article */
            padding: 20px;
            border-radius: 8px;
            background-color: #f9f9f9; /* Fond clair pour chaque article */
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        h2 {
            color: #333;
            font-size: 1.8em;
            margin-bottom: 15px;
        }
        p {
            font-size: 1.1em;
            line-height: 1.6em;
            color: #555;
            margin-bottom: 15px;
        }
        img {
            max-width: 200px;  /* Limite la largeur à 600px */
            width: 100%;       /* Prend toute la largeur disponible mais ne dépasse pas 600px */
            height: auto;      /* La hauteur est ajustée automatiquement pour garder les proportions */
            margin: 20px 0;
            border-radius: 8px;
            display: block;
        }
        .post-meta {
            font-size: 0.9em;
            color: #888;
            text-align: right;
        }
        hr {
            border: 1px solid #ddd;
        }
    </style>
</head>
<body>

    <h1>News:</h1>

    <div class="container">
        <?php foreach ($articles as $article): ?>
            <div class="post">
                <h2><?= htmlspecialchars($article['title']) ?></h2>
                <p><?= nl2br(htmlspecialchars($article['content'])) ?></p>
                
                <?php
                // Générer l'URL complète de l'image
                $imageUrl = "uploads/" . htmlspecialchars($article['image']);
                ?>

                <!-- Affichage de l'image -->
                <img src="<?= $imageUrl ?>" alt="Image de l'article">
                
                <div class="post-meta">Publié le <?= $article['created_at'] ?></div>
            </div>
        <?php endforeach; ?>
    </div>

</body>
</html>
