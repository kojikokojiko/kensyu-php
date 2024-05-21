<?php
declare(strict_types=1);
namespace App\Validation;

/**
 * Class Validator
 *
 * Provides validation methods for different types of input.
 *
 * @package App\Validation
 */
class ArticleValidator {

    /**
     * Validate article data.
     *
     * @param string|null $title The title of the article.
     * @param string|null $body The body of the article.
     * @return array An array of error messages, if any.
     */
    public function validateArticle(?string $title, ?string $body): array {
        $errors = [];

        // Check title
        if (is_null($title) || trim($title) === '') {
            $errors[] = "Title is required.";
        } elseif (strlen($title) < 5 || strlen($title) > 255) {
            $errors[] = "Title must be between 5 and 255 characters.";
            // Check if the title contains only allowed characters
            // Allowed characters:
            // - Alphanumeric characters (a-z, A-Z, 0-9)
            // - Whitespace characters (spaces, tabs, newlines)
            // - Common punctuation: . , ! ? ' " -
            // - Note: This includes underscore (_) as part of \w
            // Disallowed characters:
            // - Any special symbols not listed above (e.g., @ # $ % ^ & * ( ) + = { } [ ] | \ / < > ; : ` ~)
            // - Non-English characters and diacritics (e.g., ñ, ä, ü, é, ç)
            // - Emoji and other non-standard characters
        } elseif (!preg_match('/^[\w\s.,!?\'"-]+$/', $title)) {
            $errors[] = "Title contains invalid characters.";
        }

        // Check body
        if (is_null($body) || trim($body) === '') {
            $errors[] = "Body is required.";
        } elseif (strlen($body) < 50) {
            $errors[] = "Body must be at least 50 characters.";
        }

        return $errors;
    }
}
