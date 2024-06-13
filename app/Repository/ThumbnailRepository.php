<?php
declare(strict_types=1);

namespace App\Repository;

use PDO;
use PDOException;

class ThumbnailRepository implements RepositoryInterface
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
     * @throws PDOException If the SQL execution fails.
     */
    public function createThumbnail(int $articleId, string $path): void
    {
        $stmt = $this->db->prepare('INSERT INTO thumbnails (path, article_id) VALUES (:path, :article_id)');
        if (!$stmt->execute([
            'path' => $path,
            'article_id' => $articleId
        ])) {
            throw new PDOException('Failed to create thumbnail');
        }
    }

    /**
     * Retrieve a thumbnail by its article ID.
     *
     * @param int $articleId The ID of the article.
     * @return array|null The thumbnail record, or null if not found.
     * @throws PDOException If the SQL execution fails.
     */
    public function getThumbnailByArticleId(int $articleId): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM thumbnails WHERE article_id = :article_id');
        if (!$stmt->execute(['article_id' => $articleId])) {
            throw new PDOException('Failed to retrieve thumbnail');
        }
        $thumbnail = $stmt->fetch(PDO::FETCH_ASSOC);

        return $thumbnail ?: null;
    }

    /**
     * Update a thumbnail record.
     *
     * @param int $articleId The ID of the article to which the thumbnail belongs.
     * @param string $path The new file path of the thumbnail.
     * @return void
     * @throws PDOException If the SQL execution fails.
     */
    public function updateThumbnail(int $articleId, string $path): void
    {
        $stmt = $this->db->prepare('UPDATE thumbnails SET path = :path, updated_at = CURRENT_TIMESTAMP WHERE article_id = :article_id');
        if (!$stmt->execute([
            'path' => $path,
            'article_id' => $articleId
        ])) {
            throw new PDOException('Failed to update thumbnail');
        }
    }

    /**
     * Delete a thumbnail by its article ID.
     *
     * @param int $articleId The ID of the article.
     * @return void
     * @throws PDOException If the SQL execution fails.
     */
    public function deleteThumbnailByArticleId(int $articleId): void
    {
        $stmt = $this->db->prepare('DELETE FROM thumbnails WHERE article_id = :article_id');
        if (!$stmt->execute(['article_id' => $articleId])) {
            throw new PDOException('Failed to delete thumbnail');
        }
    }
}
