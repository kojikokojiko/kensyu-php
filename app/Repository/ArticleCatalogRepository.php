<?php
declare(strict_types=1);

namespace App\Repository;

use App\Dto\ArticleDetailDto;
use App\Dto\ArticleCatalogDto;
use App\Model\Article;
use App\Model\Category;
use PDO;

/**
 * Class ArticleRepository
 *
 * Repository for managing article data.
 *
 * @package App\Repository
 */
class ArticleCatalogRepository implements RepositoryInterface
{
    /**
     * @var PDO The PDO instance for database connection.
     */
    private $db;

    /**
     * ArticleRepository constructor.
     *
     * Initializes the repository with the given PDO instance.
     *
     * @param PDO $db The PDO instance for database connection.
     */
    public function __construct(PDO $db)
    {
        $this->db = $db;
    }



    /**
     * Get all articles with user information.
     *
     * Retrieves all articles from the database along with their associated user information.
     *
     * @return ArticleCatalogDto[] An array of ArticleWithUserDTO objects.
     */
    public function getArticleCatalogu(): array {
        $stmt = $this->db->query("
            SELECT a.id as article_id, a.title, a.body, a.user_id, u.name as user_name, u.profile_image_path, t.path as thumbnail_path
            FROM articles a
            JOIN users u ON a.user_id = u.id
            LEFT JOIN thumbnails t ON a.id = t.article_id
        ");
        $articlesData = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $articles = [];
        foreach ($articlesData as $data) {
            $articles[] = new ArticleCatalogDto(
                $data['article_id'],
                $data['title'],
                $data['body'],
                $data['user_id'],
                $data['user_name'],
                $data['profile_image_path'],
                $data['thumbnail_path'] ?? null // サムネイルが存在しない場合はnullを設定
            );
        }

        return $articles;
    }

    /**
     * Get an article by its ID along with user information.
     *
     * @param int $id The ID of the article.
     * @return ArticleCatalogDto|null The article with user information or null if not found.
     */
    public function getArticleWithUserById(int $id): ?ArticleCatalogDto
    {
        $stmt = $this->db->prepare("
            SELECT articles.id, articles.title, articles.body, articles.user_id, users.name AS user_name
            FROM articles
            JOIN users ON articles.user_id = users.id
            WHERE articles.id = :id
        ");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        return $data === false ? null : new ArticleCatalogDto($data['id'], $data['title'], $data['body'],$data['user_id'], $data['user_name']);
    }
}
