<?php
declare(strict_types=1);
namespace App\Controller;
use App\Http\Request;
use App\Http\Response;

use App\Repository\ArticleRepository;

use PDO;

/**
 * Class EditPageController
 *
 * Controller for handling the editing of articles.
 *
 * @package App\Controller
 */
class EditPageController implements ControllerInterface {
    private int $articleId;

    public function __construct(int $articleId) {
        $this->articleId = $articleId;
    }

    /**
     * Invoke action for the edit page.
     *
     * This method fetches the article by its ID, renders the edit page view,
     * and returns the response.
     *
     * @param Request $req The HTTP request object.
     * @param PDO $db The database connection object.
     * @return Response The HTTP response object containing the rendered view.
     */
    public function __invoke(Request $req, PDO $db): Response {
        $articleRepository = new ArticleRepository($db);

        $article = $articleRepository->getArticleById($this->articleId);

        if ($article) {
            ob_start();
            include __DIR__ . '/../View/edit_page.php';
            $body = ob_get_clean();
            return new Response(200, $body);
        } else {
            return new Response(404, "Article not found");
        }
    }
}
