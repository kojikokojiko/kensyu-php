<!DOCTYPE html>
<html>
<head>
    <title>Article List</title>
</head>
<body>

<!-- New Article Form -->

<form action="/article" method="POST" enctype="multipart/form-data">
    <?php if (!empty($articleWithUser->thumbnailPath)): ?>
        <img src="<?= htmlspecialchars($articleWithUser->thumbnailPath, ENT_QUOTES, 'UTF-8') ?>" alt="Thumbnail" style="width:100px;height:100px;">
    <?php endif; ?>
    <div>Title:<?php echo htmlspecialchars($articleWithUser->title); ?></div>
    <div>Body:<?php echo htmlspecialchars($articleWithUser->body); ?></div>
    <div>Created by:<?php echo htmlspecialchars($articleWithUser->userName); ?></div>
    <div>Categories:
        <?php foreach ($articleWithUser->categories as $category): ?>
            <span><?php echo htmlspecialchars($category); ?></span>
        <?php endforeach; ?>
    </div>

    <a class="submit-button" href="/">To Home</a>

    <a href="/article/<?= $articleWithUser->articleId ?>/edit">Edit</a>


</body>
</html>
