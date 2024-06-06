<?php

namespace App\Model;

class ArticleImage
{
    public function __construct(
        public int    $id,
        public int    $articleId,
        public string $path
    )
    {
    }


}
