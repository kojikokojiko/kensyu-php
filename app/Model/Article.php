<?php
declare(strict_types=1);
namespace App\Model;

use InvalidArgumentException;

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
     * @param ?int $id The ID of the article, can be null.
     * @param string $title The title of the article.
     * @param string $body The body of the article.
     */
    public function __construct(
        public ?int $id,
        public string $title,
        public string $body,
    ){
        $errors = $this->validate();
        if (!empty($errors)) {
            throw new InvalidArgumentException(implode(', ', $errors));
        }
    }


    /**
     * Validate article data.
     *
     * @return array An array of error messages, if any.
     */
    private function validate(): array {
        $errors = array_merge(
            $this->validateTitle($this->title),
            $this->validateBody($this->body)
        );

        return $errors;
    }

    /**
     * Validate the title of the article.
     *
     * @param string $title The title to validate.
     * @return array An array of error messages, if any.
     */
    private function validateTitle(string $title): array {
        $errors = [];

        if (trim($title) === '') {
            $errors[] = "Title is required.";
        } elseif (strlen($title) < 5 || strlen($title) > 255) {
            $errors[] = "Title must be between 5 and 255 characters.";
        } elseif (!preg_match('/^[\w\s.,!?\'"-]+$/', $title)) {
            $errors[] = "Title contains invalid characters.";
        }

        return $errors;
    }

    /**
     * Validate the body of the article.
     *
     * @param string $body The body to validate.
     * @return array An array of error messages, if any.
     */
    private function validateBody(string $body): array {
        $errors = [];

        if (trim($body) === '') {
            $errors[] = "Body is required.";
        } elseif (strlen($body) < 10) {
            $errors[] = "Body must be at least 50 characters.";
        }

        return $errors;
    }
}
?>
