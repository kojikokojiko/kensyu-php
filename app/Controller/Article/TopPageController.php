<?php
declare(strict_types=1);
namespace App\Controller\Article;

use App\Controller\ControllerInterface;
use App\Http\Request;
use App\Http\Response;
use App\Repository\ArticleRepository;
use Exception;
use PDO;

/**
 * Class TopPageController
 *
 * Controller for handling the home page as a single action.
 *
 * @package App\Controller
 */
class TopPageController implements ControllerInterface {

    /**
     * Invoke action for the home page.
     *
     * This method fetches all articles from the database, renders the home page view,
     * and returns the response.
     *
     * @param Request $req The HTTP request object.
     * @param PDO $db The database connection object.
     * @return Response The HTTP response object containing the rendered view.
     * @throws Exception
     */
    public function __invoke(Request $req, PDO $db): Response {
        $articleRepository = new ArticleRepository($db);
//        $articles = $articleRepository->getAllArticles();
        $articles = $articleRepository->getAllArticlesWithUser();

        ob_start();
        include __DIR__ . '/../../View/home.php';
        $body = ob_get_clean();
        return new Response(200, $body);
    }
}
?>
