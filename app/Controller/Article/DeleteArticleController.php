<?php
declare(strict_types=1);
namespace App\Controller\Article;
use App\Controller\ControllerInterface;
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
class DeleteArticleController implements ControllerInterface {
    private int $articleId;

    /**
     * DeleteArticleController constructor.
     *
     * @param int $articleId The ID of the article to be deleted.
     */
    public function __construct(int $articleId) {
        $this->articleId = $articleId;
    }



    /**
     * Invoke method to handle the HTTP request.
     *
     * @param Request $req The HTTP request object.
     * @param PDO $db The database connection object.
     * @return Response The HTTP response object.
     */
    public function __invoke(Request $req, PDO $db): Response {
        $articleRepository = new ArticleRepository($db);

        // Delete the article by its ID.
        $rowsDeleted = $articleRepository->deleteArticle($this->articleId);

        if ($rowsDeleted > 0) {
            // If deletion is successful, redirect to the article list page.
            return new Response(302, '', ['Location: /']);
        } else {
            // If the article was not found or already deleted, return a 404 response.
            return new Response(404, "Article not found or already deleted");
        }
    }
}
