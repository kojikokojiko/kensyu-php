<!DOCTYPE html>
<html>
<head>
    <title>Article List</title>
</head>
<body>

<!-- New Article Form -->
<h1>Article Detail</h1>

<div>Title:<?php echo htmlspecialchars($article['title']); ?></div>
<div>Body:<?php echo htmlspecialchars($article['body']); ?></div>
<a class="submit-button" href="/">To home</a>
<a class="submit-button" href="/article/<?= $article['id'] ?>/edit">Edit Article</a>
<form action="/article/<?= $article['id'] ?>" method="POST">
    <input type="hidden" name="_method" value="DELETE">
    <button type="submit">Delete Article</button>
</form>
</body>
</html>
