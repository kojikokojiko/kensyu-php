<!DOCTYPE html>
<html>
<head>
    <title>Edit Article</title>
    <!-- <link rel="stylesheet" type="text/css" href="styles/index.css"> -->
</head>
<body>
<h1>Edit Article</h1>
<form method="post" class="article-form" action="/article/<?= $article->id ?>/edit">
    <input type="text" id="title" name="title" value="<?= htmlspecialchars($article->title) ?>" required>
    <textarea id="body" class="input-body" name="body" required><?= htmlspecialchars($article->body) ?></textarea>
    <button type="submit" class="submit-button" id="update-button" disabled>Update</button>
</form>
<a class="submit-button" href="/article/<?= $article->id ?>">Cancel</a>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const titleInput = document.getElementById('title');
        const bodyInput = document.getElementById('body');
        const updateButton = document.getElementById('update-button');

        const originalTitle = titleInput.value;
        const originalBody = bodyInput.value;

        function checkForChanges() {
            if (titleInput.value !== originalTitle || bodyInput.value !== originalBody) {
                updateButton.disabled = false;
            } else {
                updateButton.disabled = true;
            }
        }

        titleInput.addEventListener('input', checkForChanges);
        bodyInput.addEventListener('input', checkForChanges);
    });
</script>
</body>
</html>
