<?php
declare(strict_types=1);
namespace App\Controller;

use App\Http\Request;
use App\Http\Response;
use App\Repository\ArticleRepository;
use App\Service\Database;

/**
 * Class HomeController
 *
 * Controller for handling the home page.
 *
 * @package App\Controller
 */
class HomeController {

    /**
     * Index action for the home page.
     *
     * This method fetches all articles from the database, renders the home page view,
     * and returns the response.
     *
     * @param Request $req The HTTP request object.
     * @return Response The HTTP response object containing the rendered view.
     */
    public function index(Request $req): Response {
        $db = Database::getConnection();
        $articleRepository = new ArticleRepository($db);
        $articles = $articleRepository->getAllArticles();

        ob_start();
        include __DIR__ . '/../View/home.php';
        $body = ob_get_clean();
        return new Response(200, $body);
    }
}
?>
