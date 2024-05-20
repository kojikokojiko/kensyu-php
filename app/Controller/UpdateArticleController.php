<?php

namespace App\Controller;

use App\Http\Request;
use App\Http\Response;
use App\Repository\ArticleRepository;
use PDO;

class UpdateArticleController implements ControllerInterface {
    private int $articleId;

    public function __construct(int $articleId) {
        $this->articleId = $articleId;
    }

    public function __invoke(Request $req, PDO $db): Response {
        $articleRepository = new ArticleRepository($db);
        $title = $req->post['title'];
        $body = $req->post['body'];

        if ($title && $body) {
            $rowsUpdated = $articleRepository->updateArticle($title, $body, $this->articleId);

            if ($rowsUpdated > 0) {
                // 更新が成功した場合、リダイレクトするためにLocationヘッダを設定
                return new Response(302, '', ['Location: /']);
            } else {
                // 更新が行われなかった場合
                return new Response(404, "Article not found or no changes made");
            }
        } else {
            return new Response(400, "Invalid input");
        }
    }
}
