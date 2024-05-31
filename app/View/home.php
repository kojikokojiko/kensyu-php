

<!DOCTYPE html>
<html>
<head>
    <title>Article List</title>
</head>
<body>


<?php if (!empty($_SESSION['user_id'])): ?>
    <form action="/logout" method="POST" style="display:inline;">
        <button type="submit" style="background:none; border:none; color:blue; cursor:pointer; text-decoration:underline; padding:0;">
            Log out
        </button>
    </form>
    <div>Logged in as user ID:<?= htmlspecialchars($_SESSION['user_id'], ENT_QUOTES, 'UTF-8') ?></div>
<?php else: ?>
<div class="div">
    <a href="/login">Login</a>
    <a href="/register">Register</a>
    <div>Not SignedIn</div>

</div>

<?php endif; ?>

<?php
if (!empty($_SESSION['errors'])) {
//    echo '<script>alert("Invalid input: ' . implode(", ", $_SESSION['errors']) . '");</script>';
    // Clear the errors from session
    echo "invalid input";
    unset($_SESSION['errors']);
}
?>
<h1>Article List</h1>

<!-- New Article Form -->
<h2>Create New Article</h2>
<form action="/article" method="POST" enctype="multipart/form-data">
    <label for="title">Title:</label><br>
    <input type="text" id="title" name="title" required><br><br>

    <label for="body">Body:</label><br>
    <textarea id="body" name="body" required></textarea><br><br>
<!---->
<!--    <label for="thumbnail">Thumbnail Image:</label><br>-->
<!--    <input type="file" id="thumbnail" name="thumbnail" required><br><br>-->
<!---->
<!--    <label for="images">Additional Images:</label><br>-->
<!--    <input type="file" id="images" name="images[]" multiple><br><br>-->
<!---->
<!--    <label for="tags">Tags (comma-separated):</label><br>-->
<!--    <input type="text" id="tags" name="tags"><br><br>-->

    <button type="submit">Submit</button>
</form>


<!-- Article List -->
<h2>Articles</h2>
<?php if (!empty($articles)): ?>
    <ul>
        <?php foreach ($articles as $article): ?>
            <li>
                <h2><?= htmlspecialchars($article->title, ENT_QUOTES, 'UTF-8') ?></h2>
                <p><?= nl2br(htmlspecialchars($article->body, ENT_QUOTES, 'UTF-8')) ?></p>
                <a href="/article/<?= $article->id ?>">Read more</a>
                <a href="/article/<?= $article->id ?>/edit">Edit</a>
                <form action="/article/<?= $article->id ?>" method="POST">
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
