<?php
require 'config.php';
require 'functions.php';

$id = $_GET['id'];
$article = fetchArticle($pdo, $id);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Article Details</title>
    <link rel="stylesheet" type="text/css" href="styles/article.css">
</head>
<body>
    <div class="container">
        <h1 class="article-title"><?= htmlspecialchars($article['title']) ?></h1>
        <p class="article-body"><?= nl2br(htmlspecialchars($article['body'])) ?></p>
        <a href="/" class="back-link">Back to list</a>
    </div>
</body>
</html>
