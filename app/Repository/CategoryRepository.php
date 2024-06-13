<?php
declare(strict_types=1);

namespace App\Repository;

use App\Dto\ArticleCatalogDto;
use App\Model\Article;
use App\Model\Category;
use App\TransactionManager;
use PDO;
use PDOException;

/**
 * Class ArticleRepository
 *
 * Repository for managing article data.
 *
 * @package App\Repository
 */
class CategoryRepository implements RepositoryInterface
{
    /**
     * @var PDO The PDO instance for database connection.
     */
    private $db;

    /**
     * CategoryRepository constructor.
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
     * Insert a new category for an article.
     *
     * @param Category $category
     * @return void
     */
    public function insert(Category $category): void
    {
        $stmt = $this->db->prepare("
            INSERT INTO categories (article_id, category_id)
            VALUES (:article_id, :category_id)
        ");
        $stmt->bindParam(':article_id', $category->articleId, PDO::PARAM_INT);
        $stmt->bindParam(':category_id', $category->categoryId, PDO::PARAM_INT);
        $stmt->execute();
    }

    /**
     * Insert multiple categories for an article in bulk.
     *
     * @param Category[] $categories
     * @return void
     */
    public function insertBulk(array $categories): void
    {
        $values = [];
        $placeholders = [];

        foreach ($categories as $category) {
            $values[] = $category->articleId;
            $values[] = $category->categoryId;
            $placeholders[] = "(?, ?)";
        }

        $placeholdersString = implode(", ", $placeholders);
        $stmt = $this->db->prepare("
            INSERT INTO categories (article_id, category_id)
            VALUES $placeholdersString
        ");

        $stmt->execute($values);
    }

    /**
     * Get categories by article ID.
     *
     * @param int $articleId
     * @return Category[]
     */
    public function getByArticleId(int $articleId): array
    {
        $stmt = $this->db->prepare("
            SELECT article_id, category_id
            FROM categories
            WHERE article_id = :article_id
        ");
        $stmt->bindParam(':article_id', $articleId, PDO::PARAM_INT);
        $stmt->execute();
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $categories = [];
        foreach ($data as $row) {
            $categories[] = new Category(
                (int)$row['category_id'],
                (int)$row['article_id']
            );
        }

        return $categories;
    }

    /**
     * Delete all categories for an article.
     *
     * @param int $articleId
     * @return void
     */
    public function deleteCategoriesByArticleId(int $articleId): void
    {
        $stmt = $this->db->prepare("
            DELETE FROM categories 
            WHERE article_id = :article_id
        ");
        $stmt->bindParam(':article_id', $articleId, PDO::PARAM_INT);
        $stmt->execute();
    }

    /**
     * Update categories for an article.
     *
     * @param int $articleId
     * @param Category[] $newCategories
     * @return void
     */
    public function updateCategories(int $articleId, array $newCategories, TransactionManager $transactionManager): void
    {
        try {
            $transactionManager->beginTransaction();

            $this->deleteCategoriesByArticleId($articleId);
            $this->insertBulk($newCategories);

            $transactionManager->commit();
        } catch (\RuntimeException $e) {
            $transactionManager->rollBack();
            throw $e;
        }
    }

}
