<?php
namespace App\Model;

/**
 * Class Article
 *
 * Represents an article.
 *
 * @package App\Model
 */
class Article {
    /**
     * @var int The ID of the article.
     */
    private $id;

    /**
     * @var string The title of the article.
     */
    private $title;

    /**
     * @var string The body of the article.
     */
    private $body;

    /**
     * Article constructor.
     *
     * Initializes the article with the given ID, title, and body.
     *
     * @param int $id The ID of the article.
     * @param string $title The title of the article.
     * @param string $body The body of the article.
     */
    public function __construct(int $id, string $title, string $body) {
        $this->id = $id;
        $this->title = $title;
        $this->body = $body;
    }

    /**
     * Get the ID of the article.
     *
     * @return int The ID of the article.
     */
    public function getId(): int {
        return $this->id;
    }

    /**
     * Get the title of the article.
     *
     * @return string The title of the article.
     */
    public function getTitle(): string {
        return $this->title;
    }

    /**
     * Get the body of the article.
     *
     * @return string The body of the article.
     */
    public function getBody(): string {
        return $this->body;
    }
}
?>
