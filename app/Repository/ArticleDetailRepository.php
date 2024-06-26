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
        WITH category_ids AS (
            SELECT 
                article_id, 
                ARRAY_AGG(DISTINCT category_id) AS category_ids
            FROM 
                categories
            GROUP BY 
                article_id
        ),
        image_paths AS (
            SELECT 
                article_id, 
                ARRAY_AGG(DISTINCT path) AS image_paths
            FROM 
                article_images
            GROUP BY 
                article_id
        )
        SELECT 
            articles.id AS article_id, 
            articles.title, 
            articles.body, 
            articles.user_id, 
            users.name AS user_name,
            users.profile_image_path AS profile_image_path,
            thumbnails.path AS thumbnail_path,
            COALESCE(category_ids.category_ids, '{}') AS category_ids,
            COALESCE(image_paths.image_paths, '{}') AS image_paths
        FROM 
            articles
        JOIN 
            users ON articles.user_id = users.id
        JOIN 
            thumbnails ON articles.id = thumbnails.article_id
        LEFT JOIN 
            category_ids ON articles.id = category_ids.article_id
        LEFT JOIN
            image_paths ON articles.id = image_paths.article_id
        WHERE 
            articles.id = :id
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
        $profileImagePath = $data['profile_image_path'];
        $thumbnailPath = $data['thumbnail_path'];
        $categories = [];
        $imagePaths = [];

        if (!empty($data['category_ids'])) {
            $categoryIds = trim($data['category_ids'], '{}'); // PostgreSQLの配列形式から波括弧を除去
            $categoryIdsArray = explode(',', $categoryIds);
            foreach ($categoryIdsArray as $categoryId) {
                $categories[] = new Category((int)$categoryId, $articleId);
            }
        }

        if (!empty($data['image_paths'])) {
            $imagePaths = array_map('trim', explode(',', trim($data['image_paths'], '{}'))); // PostgreSQLの配列形式から波括弧を除去
        }

        return new ArticleDetailDto(
            $articleId,
            $title,
            $body,
            $userId,
            $userName,
            $profileImagePath,
            $thumbnailPath,
            $categories,
            $imagePaths
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
        WITH category_ids AS (
            SELECT 
                article_id, 
                ARRAY_AGG(DISTINCT category_id) AS category_ids
            FROM 
                categories
            GROUP BY 
                article_id
        ),
        image_paths AS (
            SELECT 
                article_id, 
                ARRAY_AGG(DISTINCT path) AS image_paths
            FROM 
                article_images
            GROUP BY 
                article_id
        )
        SELECT 
            articles.id AS article_id, 
            articles.title, 
            articles.body, 
            articles.user_id, 
            users.name AS user_name,
            thumbnails.path AS thumbnail_path,
            COALESCE(category_ids.category_ids, '{}') AS category_ids,
            COALESCE(image_paths.image_paths, '{}') AS image_paths
        FROM 
            articles
        JOIN 
            users ON articles.user_id = users.id
        JOIN 
            thumbnails ON articles.id = thumbnails.article_id
        LEFT JOIN 
            category_ids ON articles.id = category_ids.article_id
        LEFT JOIN
            image_paths ON articles.id = image_paths.article_id
        WHERE 
            articles.id = :id AND articles.user_id = :user_id
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
        $imagePaths = [];

        if (!empty($data['category_ids'])) {
            $categoryIds = trim($data['category_ids'], '{}'); // PostgreSQLの配列形式から波括弧を除去
            $categoryIdsArray = explode(',', $categoryIds);
            foreach ($categoryIdsArray as $categoryId) {
                $categories[] = new Category((int)$categoryId, $articleId);
            }
        }

        if (!empty($data['image_paths'])) {
            $imagePaths = array_map('trim', explode(',', trim($data['image_paths'], '{}'))); // PostgreSQLの配列形式から波括弧を除去
        }

        return new ArticleDetailDto(
            $articleId,
            $title,
            $body,
            $userId,
            $userName,
            $thumbnailPath,
            $categories,
            $imagePaths
        );
    }
}
