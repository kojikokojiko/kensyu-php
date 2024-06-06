<?php
declare(strict_types=1);
namespace App\Repository;

use App\Dto\ArticleWithUserAndTagsDto;
use App\Dto\ArticleWithUserAndThumbnailDTO;
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
class ArticleRepository implements RepositoryInterface {
    /**
     * @var PDO The PDO instance for database connection.
     */
    private PDO $db;

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
            $articles[] = new Article($data['id'], $data['title'], $data['body'],$data['user_id']);
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
            SELECT a.id as article_id, a.title, a.body, a.user_id, u.name as user_name 
            FROM articles a 
            JOIN users u ON a.user_id = u.id
        ");
        $articlesData = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $articles = [];
        foreach ($articlesData as $data) {
            $articles[] = new ArticleWithUserDTO($data['article_id'], $data['title'], $data['body'], $data['user_id'], $data['user_name']);
        }

        return $articles;
    }

    /**
     * Get all articles with user and thumbnail information.
     *
     * @return array
     */
    public function getAllArticlesWithUserAndThumbnail(): array {
        $stmt = $this->db->query("
            SELECT a.id as article_id, a.title, a.body, a.user_id, u.name as user_name, t.path as thumbnail_path
            FROM articles a
            JOIN users u ON a.user_id = u.id
            LEFT JOIN thumbnails t ON a.id = t.article_id
        ");
        $articlesData = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $articles = [];
        foreach ($articlesData as $data) {
            $articles[] = new ArticleWithUserAndThumbnailDTO(
                $data['article_id'],
                $data['title'],
                $data['body'],
                $data['user_id'],
                $data['user_name'],
                [],
                $data['thumbnail_path'] ?? null // サムネイルが存在しない場合はnullを設定
            );
        }

        return $articles;
    }



    /**
     * Create a new article.
     *
     * @param Article $article The article to create.
     * @return int The ID of the newly created article.
     */
    public function createArticle(Article $article): int {
        $stmt = $this->db->prepare("INSERT INTO articles (title, body, user_id) VALUES (:title, :body, :user_id) RETURNING id");
        $stmt->bindValue(':title', $article->title);
        $stmt->bindValue(':body', $article->body);
        $stmt->bindValue(':user_id', $article->userId);
        $stmt->execute();

        return (int) $stmt->fetchColumn();
    }

    // Additional methods for article operations can be uncommented and implemented as needed.

    /**
     * Get an article by its ID.
     *
     * @param int $id The ID of the article.
     * @return Article|null The article object or null if not found.
     */
    public function getArticleById(int $id): ?Article {
        $stmt = $this->db->prepare("SELECT * FROM articles WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        return $data ? new Article($data['id'], $data['title'], $data['body'], $data['user_id']) : null;
    }

    /**
     * Get an article by its ID with user information.
     *
     * @param int $id The ID of the article.
     * @return ArticleWithUserDTO|null The article with user information DTO or null if not found.
     */
    public function getArticleByIdWithUser(int $id): ?ArticleWithUserAndTagsDto {
        $stmt = $this->db->prepare("
        SELECT a.id as article_id, a.title, a.body, a.user_id, u.name as user_name, c.name as category_name
        FROM articles a 
        JOIN users u ON a.user_id = u.id
        LEFT JOIN article_category_tagging act ON a.id = act.article_id
        LEFT JOIN categories c ON act.category_id = c.id
        WHERE a.id = :id
    ");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (!$data) {
            return null;
        }

        $articleWithUser = new ArticleWithUserAndTagsDto(
            $data[0]['article_id'],
            $data[0]['title'],
            $data[0]['body'],
            $data[0]['user_id'],
            $data[0]['user_name'],
            []
        );

        foreach ($data as $row) {
            if (isset($row['category_name'])) {
                $articleWithUser->categories[] = $row['category_name'];
            }
        }

        return $articleWithUser;
    }


    public function getArticleByIdWithUserAndThumbnail(int $id): ?ArticleWithUserAndThumbnailDTO {
        $stmt = $this->db->prepare("
            SELECT a.id as article_id, a.title, a.body, a.user_id, u.name as user_name, t.path as thumbnail_path, c.name as category_name
            FROM articles a
            JOIN users u ON a.user_id = u.id
            LEFT JOIN thumbnails t ON a.id = t.article_id
            LEFT JOIN article_category_tagging act ON a.id = act.article_id
            LEFT JOIN categories c ON act.category_id = c.id
            WHERE a.id = :id
        ");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$data) {
            return null;
        }


        $article=new ArticleWithUserAndThumbnailDTO(
            $data['article_id'],
            $data['title'],
            $data['body'],
            $data['user_id'],
            $data['user_name'],
            [],
            $data['thumbnail_path'] ?? null // サムネイルが存在しない場合はnullを設定
        );
        if (isset($data['category_name'])) {
            $article->categories[] = $data['category_name'];
        }

        return $article;
    }




    /**
     * Delete an article by its ID.
     *
     * Deletes an article from the database by its ID.
     *
     * @param int $id The ID of the article to delete.
     * @return int The number of rows affected.
     */
    public function deleteArticle(int $id): int {
        $stmt = $this->db->prepare("DELETE FROM articles WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->rowCount();
    }

    public function updateArticle(Article $article): int {
        $stmt = $this->db->prepare("UPDATE articles SET title = :title, body = :body WHERE id = :id");
        $stmt->bindValue(':title', $article->title);
        $stmt->bindValue(':body', $article->body);
        $stmt->bindValue(':id', $article->id, PDO::PARAM_INT);

        $stmt->execute();
        return $stmt->rowCount();
    }
}
