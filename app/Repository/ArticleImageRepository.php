<?php
declare(strict_types=1);

namespace App\Repository;

use App\Model\ArticleImage;
use PDO;

class ArticleImageRepository implements RepositoryInterface
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    /**
     * Create a new image for an article.
     *
     * @param int $articleId The ID of the article.
     * @param string $path The path of the image.
     */
    public function createImage(int $articleId, string $path): void
    {
        $stmt = $this->db->prepare('INSERT INTO article_images (path, article_id) VALUES (:path, :article_id)');
        $stmt->execute([
            'path' => $path,
            'article_id' => $articleId
        ]);
    }

    /**
     * Create multiple images for an article using bulk insert.
     *
     * @param int $articleId The ID of the article.
     * @param array $paths An array of image paths.
     */
    public function createImages(int $articleId, array $paths): void
    {
        if (empty($paths)) {
            return;
        }

        $values = [];
        $placeholders = [];
        foreach ($paths as $path) {
            $placeholders[] = '(?, ?)';
            $values[] = $path;
            $values[] = $articleId;
        }

        $sql = 'INSERT INTO article_images (path, article_id) VALUES ' . implode(', ', $placeholders);
        $stmt = $this->db->prepare($sql);
        $stmt->execute($values);
    }

    /**
     * Get all images for an article.
     *
     * @param int $articleId The ID of the article.
     * @return ArticleImage[] An array of ArticleImage objects.
     */
    public function getImagesByArticleId(int $articleId): array
    {
        $stmt = $this->db->prepare('SELECT id, article_id, path FROM article_images WHERE article_id = :article_id');
        $stmt->execute([
            'article_id' => $articleId
        ]);

        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $images = [];
        foreach ($results as $row) {
            $images[] = new ArticleImage((int)$row['id'], (int)$row['article_id'], $row['path']);
        }

        return $images;
    }

}
