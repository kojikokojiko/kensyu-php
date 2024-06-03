<!DOCTYPE html>
<html>
<head>
    <title>Article List</title>
</head>
<body>

<!-- New Article Form -->

<form action="/article" method="POST" enctype="multipart/form-data">

    <div>Title:<?php echo htmlspecialchars($articleData['article']->title); ?></div>
    <div>Body:<?php echo htmlspecialchars($articleData['article']->body); ?></div>
    <div>Body:<?php echo htmlspecialchars($articleData['user_name']); ?></div>
    <a class="submit-button" href="/">To Home</a>

    <a href="/article/<?= $article->id ?>/edit">Edit</a>


</body>
</html>
