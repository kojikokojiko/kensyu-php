<?php
declare(strict_types=1);

namespace App\Repository;

use PDO;

class ThumbnailRepository
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    /**
     * Create a new thumbnail record.
     *
     * @param int $articleId The ID of the article to which the thumbnail belongs.
     * @param string $path The file path of the thumbnail.
     * @return void
     */
    public function createThumbnail(int $articleId, string $path): void
    {
        $stmt = $this->db->prepare('INSERT INTO thumbnails (path, article_id) VALUES (:path, :article_id)');
        $stmt->execute([
            'path' => $path,
            'article_id' => $articleId
        ]);
    }

    /**
     * Retrieve a thumbnail by its article ID.
     *
     * @param int $articleId The ID of the article.
     * @return array|null The thumbnail record, or null if not found.
     */
    public function getThumbnailByArticleId(int $articleId): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM thumbnails WHERE article_id = :article_id');
        $stmt->execute(['article_id' => $articleId]);
        $thumbnail = $stmt->fetch(PDO::FETCH_ASSOC);

        return $thumbnail ?: null;
    }

    /**
     * Update a thumbnail record.
     *
     * @param int $articleId The ID of the article to which the thumbnail belongs.
     * @param string $path The new file path of the thumbnail.
     * @return void
     */
    public function updateThumbnail(int $articleId, string $path): void
    {
        $stmt = $this->db->prepare('UPDATE thumbnails SET path = :path, updated_at = CURRENT_TIMESTAMP WHERE article_id = :article_id');
        $stmt->execute([
            'path' => $path,
            'article_id' => $articleId
        ]);
    }

    /**
     * Delete a thumbnail by its article ID.
     *
     * @param int $articleId The ID of the article.
     * @return void
     */
    public function deleteThumbnailByArticleId(int $articleId): void
    {
        $stmt = $this->db->prepare('DELETE FROM thumbnails WHERE article_id = :article_id');
        $stmt->execute(['article_id' => $articleId]);
    }
}
