<?php
declare(strict_types=1);
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
class EditPageController implements ControllerInterface {
    private int $articleId;

    public function __construct(int $articleId) {
        $this->articleId = $articleId;
    }
    /**
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
