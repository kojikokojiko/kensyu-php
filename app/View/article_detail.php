<!DOCTYPE html>
<html>
<head>
    <title>Article List</title>
</head>
<body>

<!-- New Article Form -->

<form action="/article" method="POST" enctype="multipart/form-data">

    <div>Title:<?php echo htmlspecialchars($articleDetail->title); ?></div>
    <div>Body:<?php echo htmlspecialchars($articleDetail->body); ?></div>
    <div>UserID:<?php echo htmlspecialchars($articleDetail->userId); ?></div>
    <div>UserName:<?php echo htmlspecialchars($articleDetail->userName); ?></div>
    <?php if (!empty($articleDetail->categories)): ?>
        <div>Categories:
            <?php foreach ($articleDetail->categories as $category): ?>
                <span><?= htmlspecialchars($category->categoryName, ENT_QUOTES, 'UTF-8') ?></span>
            <?php endforeach; ?>
        </div>

    <?php endif; ?>
    <a class="submit-button" href="/">To Home</a>

    <a href="/article/<?= $articleDetail->articleId ?>/edit">Edit</a>


</body>
</html>
