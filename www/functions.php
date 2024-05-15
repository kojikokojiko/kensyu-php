<?php
function fetchArticles($pdo) {
    $stmt = $pdo->query('SELECT * FROM posts ORDER BY created_at DESC');
    return $stmt->fetchAll();
}

function fetchArticle($pdo, $id) {
    $stmt = $pdo->prepare('SELECT * FROM posts WHERE id = ?');
    $stmt->execute([$id]);
    return $stmt->fetch();
}

function createArticle($pdo, $title, $body) {
    $stmt = $pdo->prepare('INSERT INTO posts (title, body) VALUES (?, ?)');
    $stmt->execute([$title, $body]);
}
?>
