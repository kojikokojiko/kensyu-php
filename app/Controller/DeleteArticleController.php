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
    private SessionRepository $sessionRepository;

    /**
     * DeleteArticleController constructor.
     *
     * @param int $articleId The ID of the article to be deleted.
     */
    public function __construct(int $articleId, SessionRepository $sessionRepository) {
        $this->articleId = $articleId;
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

        $userId=$this->sessionRepository->get('user_id');
        // ユーザーがログインしていることを確認
        if (is_null($userId)) {
            $this->sessionRepository->setErrors(['ログインが必要です。']);

            return new Response(302, '', ['Location: /login']);
        }

        $articleRepository = new ArticleRepository($db);
        $article = $articleRepository->getArticleById($this->articleId);

        // 削除する記事がログインユーザーのものであるか確認
        if ($article === null || $article->userId !== $this->sessionRepository->get('user_id')) {
            $this->sessionRepository->setErrors(['他のユーザーの投稿は削除できません。']);

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
