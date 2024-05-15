<?php
require 'config.php';
require 'functions.php';

$id = $_GET['id'];
$article = fetchArticle($pdo, $id);

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete'])) {
    deleteArticle($pdo, $id);
    header('Location: /');
    exit();
}
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
        <form method="post" class="">
            <button class="back-link" type="submit" name="delete">Delete</button>
        </form>
        <a href="edit_article.php?id=<?= $id ?>" class="edit-link">Edit</a>
    </div>
</body>
</html>
