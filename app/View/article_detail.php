<!DOCTYPE html>
<html>
<head>
    <title>Article List</title>
</head>
<body>

<!-- New Article Form -->

<form action="/article" method="POST" enctype="multipart/form-data">
    <div>
        Thumbnail:
        <?php if (!empty($articleWithUser->thumbnailPath)): ?>
            <img src="<?= htmlspecialchars($articleWithUser->thumbnailPath, ENT_QUOTES, 'UTF-8') ?>" alt="Thumbnail" style="width:100px;height:100px;">
        <?php endif; ?>
    </div>

    <div>Title:<?php echo htmlspecialchars($articleWithUser->title); ?></div>
    <div>Body:<?php echo htmlspecialchars($articleWithUser->body); ?></div>
    <div>Created by:<?php echo htmlspecialchars($articleWithUser->userName); ?></div>
    <div>Categories:
        <?php foreach ($categories as $category): ?>
            <span><?= htmlspecialchars($category->name, ENT_QUOTES, 'UTF-8') ?></span>
        <?php endforeach; ?>
    </div>
    <div>Images:
        <?php foreach ($images as $image): ?>
            <img src="<?= htmlspecialchars($image->path, ENT_QUOTES, 'UTF-8') ?>" alt="Article Image" style="width:100px;height:100px;">
        <?php endforeach; ?>
    </div>


    <a class="submit-button" href="/">To Home</a>

    <a href="/article/<?= $articleWithUser->articleId ?>/edit">Edit</a>


</body>
</html>
