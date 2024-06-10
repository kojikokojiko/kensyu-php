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
 * Class UpdateArticleController
 *
 * Controller for handling the updating of articles.
 *
 * @package App\Controller
 */
class UpdateArticleController implements ControllerInterface
{
    private int $articleId;
    private SessionRepository $sessionRepository;

    public function __construct(int $articleId, SessionRepository $sessionRepository)
    {
        $this->articleId = $articleId;
        $this->sessionRepository = $sessionRepository;

    }

    /**
     * Invoke action for updating an article.
     *
     * This method processes the request to update an article, interacts with the repository to save it,
     * and returns the appropriate response.
     *
     * @param Request $req The HTTP request object.
     * @param PDO $db The database connection object.
     * @return Response The HTTP response object.
     */
    public function __invoke(Request $req, PDO $db): Response
    {
        $userId = $this->sessionRepository->get('user_id');

        // ユーザーがログインしていることを確認
        if (is_null($userId)) {
            $this->sessionRepository->setErrors(['ログインが必要です。']);
            return new Response(302, '', ['Location: /login']);
        }

        $articleRepository = new ArticleRepository($db);
        $article = $articleRepository->getArticleById($this->articleId);

        // 記事が存在し、かつログインユーザーのものであることを確認
        if (is_null($article) || $article->userId !== $userId) {
            $this->sessionRepository->setErrors(['他のユーザーの投稿は編集できません。']);
            return new Response(302, '', ['Location: /']);
        }

        $title = $req->post['title'];
        $body = $req->post['body'];

        try {
            $article = new Article($this->articleId, $title, $body, $userId);
            $articleRepository->updateArticle($article);
            return new Response(302, '', ['Location: /article/' . $this->articleId]);

        } catch (InvalidArgumentException $e) {
            $_SESSION['errors'] = [$e->getMessage()];
            return new Response(302, '', ['Location: /article/' . $this->articleId . '/edit']);
        }
    }
}
