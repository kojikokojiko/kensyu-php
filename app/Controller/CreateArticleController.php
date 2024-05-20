<?php

namespace App\Controller;

use App\Http\Request;
use App\Http\Response;
use App\Repository\ArticleRepository;
use PDO;

/**
 * Class CreateArticleController
 *
 * Controller for handling the creation of new articles.
 *
 * @package App\Controller
 */
class CreateArticleController implements ControllerInterface {

    /**
     * Invoke action for creating a new article.
     *
     * This method processes the request to create a new article, interacts with the repository to save it,
     * and returns the appropriate response.
     *
     * @param Request $req The HTTP request object.
     * @param PDO $db The database connection object.
     * @return Response The HTTP response object.
     */
    public function __invoke(Request $req, PDO $db): Response {
        $articleRepository = new ArticleRepository($db);
        $title = $req->post['title'];
        $body = $req->post['body'];

        if ($title && $body) {
            $articleId = $articleRepository->createArticle($title, $body);
            // Redirect to the home page after successful creation
            return new Response(302, '', ['Location: /']);
        } else {
            return new Response(400, "Invalid input");
        }
    }
}
