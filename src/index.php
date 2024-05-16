<?php
require 'config.php';
require 'functions.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    createArticle($pdo, $_POST['title'], $_POST['body']);
    header('Location: /');
    exit();
}

$articles = fetchArticles($pdo);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Article List</title>
    <link rel="stylesheet" type="text/css" href="styles/index.css">
</head>
<body>
    <h1 class="title">Article List</h1>
    <form method="post" class="article-form">
        <input type="text" name="title" placeholder="Title" required class="input-title">
        <textarea name="body" placeholder="Body" required class="input-body"></textarea>
        <button type="submit" class="submit-button">Submit</button>
    </form>
    <ul class="article-list">
        <?php foreach ($articles as $article): ?>
            <li class="article-item">
                <a href="article.php?id=<?= $article['id'] ?>" class="article-link">
                    <div class="article-title"><?= htmlspecialchars($article['title']) ?></div>
                    <div class="article_body"><?= htmlspecialchars($article['body']) ?></div>   
                </a>
            </li>
        <?php endforeach; ?>
    </ul>
</body>
</html>
