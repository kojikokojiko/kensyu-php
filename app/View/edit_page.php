<!DOCTYPE html>
<html>
<head>
    <title>Edit Article</title>
    <!-- <link rel="stylesheet" type="text/css" href="styles/index.css"> -->
</head>
<body>
<h1>Edit Article</h1>

<?php
// セッションからエラーメッセージを取得してクリア
$errors = isset($_SESSION['errors']) ? $_SESSION['errors'] : [];
unset($_SESSION['errors']);
?>
<!-- エラーメッセージの表示 -->
<?php if (!empty($errors)): ?>
    <div class="alert alert-danger">
        <ul>
            <?php foreach ($errors as $error): ?>
                <li><?= htmlspecialchars($error) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<form method="post" class="article-form" action="/article/<?= $article->id ?>">
    <input type="hidden" name="_method" value="PUT">
    <input type="text" id="title" name="title" value="<?= htmlspecialchars($article->title) ?>" required>
    <textarea id="body" class="input-body" name="body" required><?= htmlspecialchars($article->body) ?></textarea>

    <?php if (!empty($allCategories)): ?>
        <?php foreach ($allCategories as $categoryId => $categoryName): ?>
            <ul style="list-style-type: none;">
                <li>
                    <input type="checkbox" id="categoryIds" name="categoryIds[]" value="<?= htmlspecialchars($categoryId, ENT_QUOTES, 'UTF-8') ?>"
                        <?= in_array($categoryId, $existingCategoryIds) ? 'checked' : '' ?>>
                    <?= htmlspecialchars($categoryName, ENT_QUOTES, 'UTF-8') ?>
                </li>
            </ul>
        <?php endforeach; ?>
    <?php endif; ?>

    <button type="submit" class="submit-button" id="update-button" disabled>Update</button>
</form>
<a class="submit-button" href="/article/<?= $article->id ?>">Cancel</a>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const titleInput = document.getElementById('title');
        const bodyInput = document.getElementById('body');
        const updateButton = document.getElementById('update-button');
        const checkboxes = document.querySelectorAll('input[name="categoryIds[]"]');

        const originalTitle = titleInput.value;
        const originalBody = bodyInput.value;
        const originalCheckboxes = Array.from(checkboxes).map(checkbox => checkbox.checked);

        function checkForChanges() {
            const titleChanged = titleInput.value !== originalTitle;
            const bodyChanged = bodyInput.value !== originalBody;
            const checkboxesChanged = Array.from(checkboxes).some((checkbox, index) => checkbox.checked !== originalCheckboxes[index]);

            updateButton.disabled = !(titleChanged || bodyChanged || checkboxesChanged);
        }

        titleInput.addEventListener('input', checkForChanges);
        bodyInput.addEventListener('input', checkForChanges);
        checkboxes.forEach(checkbox => {
            checkbox.addEventListener('change', checkForChanges);
        });
    });
</script>
</body>
</html>
