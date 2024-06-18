<?php

namespace App\Dto;

/**
 * Class ArticleWithUserDTO
 *
 * Data Transfer Object for Article with User information.
 *
 * @package App\DTO
 */
class ArticleDetailDto
{
    public function __construct(
        public int     $articleId,
        public string  $title,
        public string  $body,
        public int     $userId,
        public string  $userName,
        public string  $profileImagePath,
        public ?string $thumbnailPath,
        public array   $categories,
        public array   $imagePaths
    )
    {
    }
}
