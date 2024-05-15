<?php
function fetchArticles($pdo) {
    $stmt = $pdo->query('SELECT * FROM articles ORDER BY created_at DESC');
    return $stmt->fetchAll();
}

function fetchArticle($pdo, $id) {
    $stmt = $pdo->prepare('SELECT * FROM articles WHERE id = ?');
    $stmt->execute([$id]);
    return $stmt->fetch();
}

function createArticle($pdo, $title, $body) {
    $stmt = $pdo->prepare('INSERT INTO articles (title, body) VALUES (?, ?)');
    $stmt->execute([$title, $body]);
}
?>