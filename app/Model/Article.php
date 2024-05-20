<?php
declare(strict_types=1);
namespace App\Model;

/**
 * Class Article
 *
 * Represents an article.
 *
 * @package App\Model
 */
readonly class Article {

    /**
     * Article constructor.
     *
     * Initializes the article with the given ID, title, and body.
     *
     * @param int $id The ID of the article.
     * @param string $title The title of the article.
     * @param string $body The body of the article.
     */
    public function __construct(
        public int $id,
        public string $title,
        public string $body,
    ){}
}
?>
