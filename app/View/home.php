<!DOCTYPE html>
<html>
<head>
    <title>Article List</title>
</head>
<body>
<h1>Article List</h1>

<!-- New Article Form -->
<h2>Create New Article</h2>
<form action="/article" method="POST" enctype="multipart/form-data">
    <label for="title">Title:</label><br>
    <input type="text" id="title" name="title" required><br><br>

    <label for="body">Body:</label><br>
    <textarea id="body" name="body" required></textarea><br><br>

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

<ul>
    <?php foreach ($articles as $article): ?>
        <li>
            <a href="/article/<?php echo $article['id']; ?>"><?php echo htmlspecialchars($article['title']); ?></a>
            <!-- DELETE Article Form -->
            <form action="/article/<?= $article['id'] ?>" method="POST">
                <input type="hidden" name="_method" value="DELETE">
                <button type="submit">Delete Article</button>
            </form>
        </li>
    <?php endforeach; ?>
</ul>
</body>
</html>
