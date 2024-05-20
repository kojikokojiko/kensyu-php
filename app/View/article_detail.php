<!DOCTYPE html>
<html>
<head>
    <title>Article List</title>
</head>
<body>

<!-- New Article Form -->

<form action="/article" method="POST" enctype="multipart/form-data">

    <div>Title:<?php echo htmlspecialchars($article['title']); ?></div>
    <div>Body:<?php echo htmlspecialchars($article['body']); ?></div>
</body>
</html>
