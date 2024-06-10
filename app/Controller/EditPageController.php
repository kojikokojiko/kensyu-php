<?php
declare(strict_types=1);

namespace App\Controller;

use App\Http\Request;
use App\Http\Response;
use App\Repository\ArticleRepository;
use App\Repository\SessionRepository;
use PDO;

/**
 * Class EditPageController
 *
 * Controller for handling the editing of articles.
 *
 * @package App\Controller
 */
class EditPageController implements ControllerInterface
{
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
     * Invoke action for the edit page.
     *
     * This method fetches the article by its ID, renders the edit page view,
     * and returns the response.
     *
     * @param Request $req The HTTP request object.
     * @param PDO $db The database connection object.
     * @return Response The HTTP response object containing the rendered view.
     */
    public function __invoke(Request $req, PDO $db): Response
    {
        $articleRepository = new ArticleRepository($db);
        $article = $articleRepository->getArticleById($this->articleId);

        // 記事が存在し、かつログインユーザーのものであることを確認
        if (is_null($article) || $article->userId !== $this->userId) {
            $this->sessionRepository->setErrors(['他のユーザーの投稿は編集できません。']);
            return new Response(302, '', ['Location: /']);
        }

        ob_start();
        include __DIR__ . '/../View/edit_page.php';
        $body = ob_get_clean();
        return new Response(200, $body);
    }
}
