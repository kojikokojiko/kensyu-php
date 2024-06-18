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
    <div style="display: flex; align-items: center;">
        <?php if (!empty($articleDetail->profileImagePath)): ?>
            <img src="<?= htmlspecialchars($articleDetail->profileImagePath, ENT_QUOTES, 'UTF-8') ?>" alt="ProfileImage" style="width:100px;height:100px;border-radius:50%; margin-right: 10px;">
        <?php endif; ?>
        <p>UserName: <?= nl2br(htmlspecialchars($articleDetail->userName, ENT_QUOTES, 'UTF-8')) ?></p>
    </div>
    <?php if (!empty($articleDetail->thumbnailPath)): ?>
        <img src="<?= htmlspecialchars($articleDetail->thumbnailPath, ENT_QUOTES, 'UTF-8') ?>" alt="Thumbnail" style="width:100px;height:100px;">
    <?php endif; ?>
    <div>Images:
        <?php foreach ($articleDetail->imagePaths as $imagePath): ?>
            <img src="<?= htmlspecialchars($imagePath, ENT_QUOTES, 'UTF-8') ?>" alt="Article Image" style="width:100px;height:100px;">
        <?php endforeach; ?>
    </div>
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
