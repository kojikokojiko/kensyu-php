<?php
declare(strict_types=1);

namespace App\Dto;

class ArticleWithUserAndThumbnailDTO
{
    public function __construct(
        public int     $articleId,
        public string  $title,
        public string  $body,
        public int     $userId,
        public string  $userName,
        public array   $categories,
        public ?string $thumbnailPath // サムネイルパスは存在しない場合もあるのでnullableにする
    )
    {
    }
}
