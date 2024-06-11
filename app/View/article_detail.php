<!DOCTYPE html>
<html>
<head>
    <title>Article List</title>
</head>
<body>

<!-- New Article Form -->

<form action="/article" method="POST" enctype="multipart/form-data">

    <div>Title:<?php echo htmlspecialchars($articleWithUser->title); ?></div>
    <div>Body:<?php echo htmlspecialchars($articleWithUser->body); ?></div>
    <div>UserID:<?php echo htmlspecialchars($articleWithUser->userId); ?></div>
    <div>UserName:<?php echo htmlspecialchars($articleWithUser->userName); ?></div>
    <?php if (!empty($categories)): ?>


        <div>Categories:
            <?php foreach ($categories as $category): ?>
                <span><?= htmlspecialchars($category->categoryName, ENT_QUOTES, 'UTF-8') ?></span>
            <?php endforeach; ?>
        </div>

    <?php endif; ?>
    <a class="submit-button" href="/">To Home</a>

    <a href="/article/<?= $articleWithUser->id ?>/edit">Edit</a>


</body>
</html>
