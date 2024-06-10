<?php
declare(strict_types=1);
namespace App\Controller;
use App\Http\Request;
use App\Http\Response;
use App\Model\Article;
use App\Repository\ArticleRepository;
use App\Repository\SessionRepository;
use InvalidArgumentException;
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
    private int $userId;
    private SessionRepository $sessionRepository;


    public function __construct(int $articleId, int $userId, SessionRepository $sessionRepository)
    {
        $this->articleId = $articleId;
        $this->userId = $userId;
        $this->sessionRepository = $sessionRepository;
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
        $article = $articleRepository->getArticleByIdAndUserId($this->articleId, $this->userId);

        // 記事が存在し、かつログインユーザーのものであることを確認
        if (is_null($article)) {
            $this->sessionRepository->setErrors(['他のユーザーの投稿は編集できません。']);
            return new Response(302, '', ['Location: /']);
        }
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
