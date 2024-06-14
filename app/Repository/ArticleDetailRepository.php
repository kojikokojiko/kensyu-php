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
class ArticleDetailRepository implements RepositoryInterface
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
     * Get an article by its ID.
     *
     * Retrieves an article from the database by its ID.
     *
     * @param int $id The ID of the article to retrieve.
     * @return ArticleCatalogDto|null The article with user information, or null if not found.
     */
    public function getArticleDetailById(int $id): ?ArticleDetailDto
    {
        $stmt = $this->db->prepare("
            SELECT 
                articles.id AS article_id, 
                articles.title, 
                articles.body, 
                articles.user_id, 
                users.name AS user_name,
                thumbnails.path AS thumbnail_path,
                ARRAY_AGG(categories.category_id) AS category_ids
            FROM 
                articles
            JOIN 
                users ON articles.user_id = users.id
            JOIN 
                thumbnails ON articles.id = thumbnails.article_id
            LEFT JOIN 
                categories ON articles.id = categories.article_id
            WHERE 
                articles.id = :id
            GROUP BY 
                articles.id, 
                articles.title, 
                articles.body, 
                articles.user_id, 
                users.name,
                thumbnails.path
        ");

        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($data === false) {
            return null;
        }

        $articleId = (int)$data['article_id'];
        $title = $data['title'];
        $body = $data['body'];
        $userId = (int)$data['user_id'];
        $userName = $data['user_name'];
        $thumbnailPath = $data['thumbnail_path'];
        $categories = [];

        if (!empty($data['category_ids'])) {
            $categoryIds = trim($data['category_ids'], '{}'); // PostgreSQLの配列形式から波括弧を除去
            $categoryIdsArray = explode(',', $categoryIds);
            foreach ($categoryIdsArray as $categoryId) {
                $categories[] = new Category((int)$categoryId, $articleId);
            }
        }

        return new ArticleDetailDto(
            $articleId,
            $title,
            $body,
            $userId,
            $userName,
            $thumbnailPath,
            $categories
        );
    }

    /**
     * Get an article by its ID and User ID.
     *
     * Retrieves an article from the database by its ID and User ID.
     *
     * @param int $id The ID of the article to retrieve.
     * @param int $userId The ID of the user who owns the article.
     * @return ArticleDetailDto|null The article with user information, or null if not found.
     */
    public function getArticleDetailByIdAndUserId(int $id, int $userId): ?ArticleDetailDto
    {
        $stmt = $this->db->prepare("
            SELECT 
                articles.id AS article_id, 
                articles.title, 
                articles.body, 
                articles.user_id, 
                users.name AS user_name,
                thumbnails.path AS thumbnail_path,
                ARRAY_AGG(categories.category_id) AS category_ids
            FROM 
                articles
            JOIN 
                users ON articles.user_id = users.id
            JOIN 
                thumbnails ON articles.id = thumbnails.article_id
            LEFT JOIN 
                categories ON articles.id = categories.article_id
            WHERE 
                articles.id = :id AND articles.user_id = :user_id
            GROUP BY 
                articles.id, 
                articles.title, 
                articles.body, 
                articles.user_id, 
                users.name,
                thumbnails.path
        ");

        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->execute();
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($data === false) {
            return null;
        }

        $articleId = (int)$data['article_id'];
        $title = $data['title'];
        $body = $data['body'];
        $userId = (int)$data['user_id'];
        $userName = $data['user_name'];
        $thumbnailPath = $data['thumbnail_path'];
        $categories = [];

        if (!empty($data['category_ids'])) {
            $categoryIds = trim($data['category_ids'], '{}'); // PostgreSQLの配列形式から波括弧を除去
            $categoryIdsArray = explode(',', $categoryIds);
            foreach ($categoryIdsArray as $categoryId) {
                $categories[] = new Category((int)$categoryId, $articleId);
            }
        }

        return new ArticleDetailDto(
            $articleId,
            $title,
            $body,
            $userId,
            $userName,
            $thumbnailPath,
            $categories
        );
    }
}
