<?php
declare(strict_types=1);
namespace App\Controller;

use App\Exceptions\RepositoryException;
use App\Http\Request;
use App\Http\Response;
use App\Model\Article;
use App\Repository\ArticleRepository;
use InvalidArgumentException;
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

        try{
            $article= new Article(null,$title, $body);
            $articleId = $articleRepository->createArticle($article);
            // Redirect to the home page after successful creation
            return new Response(302, '', ['Location: /']);

        }catch (InvalidArgumentException $e){
            $_SESSION['errors'] = [$e->getMessage()];
            return new Response(302, '', ['Location: /']);
        }


    }
}
