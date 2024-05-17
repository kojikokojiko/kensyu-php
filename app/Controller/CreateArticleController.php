<?php

namespace App\Controller;

use App\Http\Request;
use App\Http\Response;
use App\Repository\ArticleRepository;
use PDO;

class CreateArticleController implements ControllerInterface {

    public function __invoke(Request $req, PDO $db): Response {
        $articleRepository = new ArticleRepository($db);
        $title = $req->get['title'];
        $body = $req->get['body'];

        if ($title && $body) {
            $articleId = $articleRepository->createArticle($title, $body);
            // リダイレクトするためにLocationヘッダを設定
            return new Response(302, '', ['Location: /']);
        } else {
            return new Response(400, "Invalid input");
        }
    }


}
