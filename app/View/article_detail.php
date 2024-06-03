<!DOCTYPE html>
<html>
<head>
    <title>Article List</title>
</head>
<body>

<!-- New Article Form -->

<form action="/article" method="POST" enctype="multipart/form-data">

    <div>Title:<?php echo htmlspecialchars($articleDTO->title); ?></div>
    <div>Body:<?php echo htmlspecialchars($articleDTO->body); ?></div>
    <div>Created by:<?php echo htmlspecialchars($articleDTO->userName); ?></div>

    <a class="submit-button" href="/">To Home</a>

    <a href="/article/<?= $articleDTO->id ?>/edit">Edit</a>


</body>
</html>
