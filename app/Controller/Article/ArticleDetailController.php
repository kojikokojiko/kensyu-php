<?php
declare(strict_types=1);
namespace App\Controller\Article;

use App\Controller\ControllerInterface;
use App\Http\Request;
use App\Http\Response;
use App\Repository\ArticleCategoryRepository;
use App\Repository\ArticleImagesRepository;
use App\Repository\ArticleRepository;
use PDO;

/**
 * Class ArticleDetailController
 *
 * Controller for handling the creation of new articles.
 *
 * @package App\Controller
 */
class ArticleDetailController implements ControllerInterface
{

    private int $articleId;

    public function __construct(int $articleId) {
        $this->articleId = $articleId;
    }

    /**
     * Invoke action for the home page.
     *
     * This method fetches all articles from the database, renders the home page view,
     * and returns the response.
     *
     * @param Request $req The HTTP request object.
     * @param PDO $db The database connection object.
     * @return Response The HTTP response object containing the rendered view.
     */
    public function __invoke(Request $req, PDO $db): Response {
        $articleRepository = new ArticleRepository($db);
        $articleCategoryRepository= new ArticleCategoryRepository($db);
        $articleImagesRepository= new ArticleImagesRepository($db);
        // getArticleByIdWithUserメソッドを使用して記事とユーザー情報を取得
        $articleWithUser = $articleRepository->getArticleByIdWithUserAndThumbnail($this->articleId);
        $categories=$articleCategoryRepository->getCategoriesByArticleId($this->articleId);
        $images=$articleImagesRepository->getImagesByArticleId($this->articleId);



        if ($articleWithUser) {
            ob_start();
            include __DIR__ . '/../../View/article_detail.php';
            $body = ob_get_clean();
            return new Response(200, $body);
        } else {
            return new Response(404, "Article not found");
        }
    }
}
