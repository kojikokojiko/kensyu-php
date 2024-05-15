<?php
require __DIR__ . '/config.php';
require 'functions.php';

$id = $_GET['id'];
$article = fetchArticle($pdo, $id);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    updateArticle($pdo, $id, $_POST['title'], $_POST['body']);
    header('Location: article.php?id=' . $id);
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Article</title>
    <link rel="stylesheet" type="text/css" href="styles/index.css">
    
</head>
<body>
    <h1>Edit Article</h1>
    <form method="post" class="article-form">
        <input type="text" name="title" value="<?= htmlspecialchars($article['title']) ?>" required>
        <textarea class="input-body" name="body" required><?= htmlspecialchars($article['body']) ?></textarea>
        <button type="submit" class="submit-button">Update</button>
    </form>
    <a  class="submit-button" href="article.php?id=<?= $article['id'] ?>">Back to article</a>
</body>
</html>
