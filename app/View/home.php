<!DOCTYPE html>
<html>
<head>
    <title>Article List</title>
</head>
<body>

<?php if (!empty($_SESSION['user_id'])): ?>
    <form action="/logout" method="POST" style="display:inline;">
        <button type="submit"
                style="background:none; border:none; color:blue; cursor:pointer; text-decoration:underline; padding:0;">
            Log out
        </button>
    </form>
    <div>Logged in as user ID:<?= htmlspecialchars($_SESSION['user_id'], ENT_QUOTES, 'UTF-8') ?></div>
<?php else: ?>
    <div class="div">
<!--        <a href="/login">Login</a>-->
        <a href="/login">Login</a>
        <a href="/register">Register</a>

        <div>Not SignedIn</div>

    </div>
<?php endif; ?>

<?php
if (!empty($_SESSION['errors'])) {
    $errors = $_SESSION['errors'];
    unset($_SESSION['errors']); // エラーメッセージをクリア
}
?>



<?php if (!empty($errors)): ?>
    <div class="alert alert-danger">
        <ul>
            <?php foreach ($errors as $error): ?>
                <li><?= htmlspecialchars($error) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>
<h1>Article List</h1>

<!-- New Article Form -->
<h2>Create New Article</h2>
<form action="/article" method="POST" enctype="multipart/form-data">
    <label for="title">Title:</label><br>
    <input type="text" id="title" name="title" required><br><br>

    <label for="body">Body:</label><br>
    <textarea id="body" name="body" required></textarea><br><br>
    <label for="thumbnail">Thumbnail:</label><br>
    <input type="file" id="thumbnails" name="thumbnails" accept="image/*" required><br><br>
    <label for="article_images">Images:</label><br>
    <input type="file" id="article_images" name="article_images[]" multiple><br><br>
    <!---->
    <!--    <label for="thumbnail">Thumbnail Image:</label><br>-->
    <!--    <input type="file" id="thumbnail" name="thumbnail" required><br><br>-->
    <!---->
    <!--    <label for="images">Additional Images:</label><br>-->
    <!--    <input type="file" id="images" name="images[]" multiple><br><br>-->
    <!---->
    <!--    <label for="tags">Tags (comma-separated):</label><br>-->
    <!--    <input type="text" id="tags" name="tags"><br><br>-->
    <?php if (!empty($allCategories)): ?>


        <?php foreach ($allCategories as $categoryId => $categoryName): ?>
            <ul style="list-style-type: none;">
                <li>
                    <input type="checkbox" id="categoryIds" name="categoryIds[]" value="<?= $categoryId ?>"><?= $categoryName ?>
                </li>
            </ul>
        <?php endforeach; ?>

    <?php endif; ?>
    <button type="submit">Submit</button>
</form>

<!-- Article List -->
<h2>Articles</h2>
<?php if (!empty($articlesCatalog)): ?>
    <ul>
        <?php foreach ($articlesCatalog as $article): ?>
            <li>
                <h2><?= htmlspecialchars($article->title, ENT_QUOTES, 'UTF-8') ?></h2>
                <p><?= nl2br(htmlspecialchars($article->body, ENT_QUOTES, 'UTF-8')) ?></p>
                <p>UserId:<?= nl2br(htmlspecialchars($article->userId, ENT_QUOTES, 'UTF-8')) ?></p>
                <div style="display: flex; align-items: center;">
                    <?php if (!empty($article->profileImagePath)): ?>
                        <img src="<?= htmlspecialchars($article->profileImagePath, ENT_QUOTES, 'UTF-8') ?>" alt="ProfileImage" style="width:100px;height:100px;border-radius:50%; margin-right: 10px;">
                    <?php endif; ?>
                    <p>UserName: <?= nl2br(htmlspecialchars($article->userName, ENT_QUOTES, 'UTF-8')) ?></p>
                </div>
                <?php if (!empty($article->thumbnailPath)): ?>
                    <img src="<?= htmlspecialchars($article->thumbnailPath, ENT_QUOTES, 'UTF-8') ?>" alt="Thumbnail" style="width:100px;height:100px;">
                <?php endif; ?>
                <a href="/article/<?= $article->articleId ?>">Read more</a>
                <a href="/article/<?= $article->articleId ?>/edit">Edit</a>
                <form action="/article/<?= $article->articleId ?>" method="POST">
                    <input type="hidden" name="_method" value="DELETE">
                    <button type="submit">Delete Article</button>
                </form>
            </li>
        <?php endforeach; ?>
    </ul>
<?php else: ?>
    <p>No articles found.</p>
<?php endif; ?>

</body>
</html>
