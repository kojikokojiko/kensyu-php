<?php
declare(strict_types=1);

namespace App\Repository;

use App\Dto\ArticleWithUserDto;
use App\Model\Article;
use PDO;

/**
 * Class ArticleRepository
 *
 * Repository for managing article data.
 *
 * @package App\Repository
 */
class ArticleRepository implements RepositoryInterface
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
     * Get all articles.
     *
     * Retrieves all articles from the database.
     *
     * @return Article[] An array of Article objects.
     */
    public function getAllArticles(): array
    {
        $stmt = $this->db->query("SELECT * FROM articles");
        $articlesData = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $articles = [];
        foreach ($articlesData as $data) {
            $articles[] = new Article($data['id'], $data['title'], $data['body'], $data['user_id']);
        }

        return $articles;
    }


    /**
     * Get all articles with user information.
     *
     * Retrieves all articles from the database along with their associated user information.
     *
     * @return ArticleWithUserDTO[] An array of ArticleWithUserDTO objects.
     */
    public function getAllArticlesWithUser(): array {
        $stmt = $this->db->query("
            SELECT articles.id as article_id, articles.title, articles.body, articles.user_id, users.name as user_name 
            FROM articles
            JOIN users ON articles.user_id = users.id
        ");
        $articlesData = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $articles = [];
        foreach ($articlesData as $data) {
            $articles[] = new ArticleWithUserDTO($data['article_id'], $data['title'], $data['body'], $data['user_id'], $data['user_name']);
        }

        return $articles;
    }
    /**
     * Create a new article.
     *
     * @param Article $article The article to create.
     * @return int The ID of the newly created article.
     */
    public function createArticle(Article $article): int
    {
        $stmt = $this->db->prepare("INSERT INTO articles (title, body, user_id) VALUES (:title, :body, :user_id) RETURNING id");
        $stmt->bindValue(':title', $article->title);
        $stmt->bindValue(':body', $article->body);
        $stmt->bindValue(':user_id', $article->userId);
        $stmt->execute();

        return (int)$stmt->fetchColumn();
    }

    // Additional methods for article operations can be uncommented and implemented as needed.

    /**
     * Get an article by its ID.
     *
     * @param int $id The ID of the article.
     * @return Article|null The article object or null if not found.
     */
    public function getArticleById(int $id): ?Article
    {
        $stmt = $this->db->prepare("SELECT * FROM articles WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        return $data ? new Article($data['id'], $data['title'], $data['body'],$data['user_id']) : null;
    }

    /**
     * Get an article by its ID along with user information.
     *
     * @param int $id The ID of the article.
     * @return ArticleWithUserDTO|null The article with user information or null if not found.
     */
    public function getArticleWithUserById(int $id): ?ArticleWithUserDto
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

        return $data ? new ArticleWithUserDto($data['id'], $data['title'], $data['body'],$data['user_id'], $data['user_name']) : null;
    }

    /**
     * Get an article by its ID and user ID.
     *
     * @param int $id The ID of the article.
     * @param int $userId The ID of the user.
     * @return Article|null The article object or null if not found.
     */
    public function getArticleByIdAndUserId(int $id, int $userId): ?Article
    {
        $stmt = $this->db->prepare("SELECT * FROM articles WHERE id = :id AND user_id = :user_id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->execute();
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        return $data ? new Article((int) $data['id'], $data['title'], $data['body'], (int) $data['user_id']) : null;
    }

    /**
     * Delete an article by its ID.
     *
     * Deletes an article from the database by its ID.
     *
     * @param int $id The ID of the article to delete.
     * @return int The number of rows affected.
     */
    public function deleteArticle(int $id): int
    {
        $stmt = $this->db->prepare("DELETE FROM articles WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->rowCount();
    }

    /**
     * Update an article.
     *
     * Updates an existing article in the database.
     *
     * @param Article $article The article to update.
     */
    public function updateArticle(Article $article): void
    {
        $stmt = $this->db->prepare("UPDATE articles SET title = :title, body = :body WHERE id = :id");
        $stmt->bindValue(':title', $article->title);
        $stmt->bindValue(':body', $article->body);
        $stmt->bindValue(':id', $article->id, PDO::PARAM_INT);

        $stmt->execute();
    }
}
