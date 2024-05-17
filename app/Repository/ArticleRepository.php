<?php
declare(strict_types=1);
namespace App\Repository;

use App\Model\Article;
use App\Repository\RepositoryInterface;
use PDO;

/**
 * Class ArticleRepository
 *
 * Repository for managing article data.
 *
 * @package App\Repository
 */
class ArticleRepository implements RepositoryInterface {
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
    public function __construct(PDO $db) {
        $this->db = $db;
    }

    /**
     * Get all articles.
     *
     * Retrieves all articles from the database.
     *
     * @return Article[] An array of Article objects.
     */
    public function getAllArticles(): array {
        $stmt = $this->db->query("SELECT * FROM articles");
        $articlesData = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $articles = [];
        foreach ($articlesData as $data) {
            $articles[] = new Article($data['id'], $data['title'], $data['body']);
        }

        return $articles;
    }
    // Additional methods for article operations can be uncommented and implemented as needed.

    // /**
    //  * Get an article by its ID.
    //  *
    //  * @param int $id The ID of the article.
    //  * @return array|null The article data or null if not found.
    //  */
    // public function getArticleById(int $id): ?array {
    //     $stmt = $this->db->prepare("SELECT * FROM articles WHERE id = :id");
    //     $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    //     $stmt->execute();
    //     return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    // }

    // /**
    //  * Create a new article.
    //  *
    //  * @param string $title The title of the article.
    //  * @param string $body The body of the article.
    //  * @param string $thumbnailPath The path to the thumbnail image.
    //  * @return int The ID of the newly created article.
    //  */
     public function createArticle(string $title, string $body): int {
         $stmt = $this->db->prepare("INSERT INTO articles (title, body) VALUES (:title, :body) RETURNING id");
         $stmt->bindParam(':title', $title);
         $stmt->bindParam(':body', $body);
         $stmt->execute();
         return $stmt->fetchColumn();
     }
}
?>
