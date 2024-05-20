<!DOCTYPE html>
<html>
<head>
    <title>Article List</title>
</head>
<body>

<!-- New Article Form -->
<h1>Article Detail</h1>

<form action="/article" method="POST" enctype="multipart/form-data">

    <div>Title:<?php echo htmlspecialchars($article['title']); ?></div>
    <div>Body:<?php echo htmlspecialchars($article['body']); ?></div>
    <a class="submit-button" href="/">To home</a>
    <a class="submit-button" href="/article/<?= $article['id'] ?>/edit">Edit Article</a>

</body>
</html>
