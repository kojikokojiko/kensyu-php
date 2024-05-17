<?php
namespace App\Controller;

use App\Http\Request;
use App\Http\Response;
use App\Repository\ArticleRepository;
use App\Service\Database;

/**
 * Class ArticleController
 *
 * Controller for handling article-related actions.
 *
 * @package App\Controller
 */
class ArticleController {

//    public function show(Request $req): Response {
//        $db = Database::getConnection();
//        $articleRepository = new ArticleRepository($db);
//        $article = $articleRepository->getArticleById($req->getQueryParams()['id']);
//
//        if ($article) {
//            ob_start();
//            include __DIR__ . '/../View/article.php';
//            $body = ob_get_clean();
//            return new Response(200, $body);
//        } else {
//            return new Response(404, "Article not found");
//        }
//    }
    /**
     * Create a new article.
     *
     * @param Request $req The HTTP request object.
     * @return Response The HTTP response object.
     */
    public function store(Request $req): Response {
        $db = Database::getConnection();
        $articleRepository = new ArticleRepository($db);

        $title = $req->getPostParams()['title'] ?? '';
        $body = $req->getPostParams()['body'] ?? '';

        if ($title && $body) {
            $articleId = $articleRepository->createArticle($title, $body);

            // リダイレクトするためにLocationヘッダを設定
            return new Response(302, '', ['Location: /']);
        } else {
            return new Response(400, "Invalid input");
        }
    }
}
?>
