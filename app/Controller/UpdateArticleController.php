<?php
declare(strict_types=1);

namespace App\Controller;

use App\Http\Request;
use App\Http\Response;
use App\Model\Article;
use App\Model\Category;
use App\Repository\ArticleRepository;
use App\Repository\CategoryRepository;
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
    private int $userId;
    private SessionRepository $sessionRepository;


    public function __construct(int $articleId, int $userId, SessionRepository $sessionRepository)
    {
        $this->articleId = $articleId;
        $this->userId = $userId;
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
        $articleRepository = new ArticleRepository($db);
        $categoryRepository = new CategoryRepository($db);

        $article = $articleRepository->getArticleByIdAndUserId($this->articleId, $this->userId);


        // 記事が存在し、かつログインユーザーのものであることを確認
        if (is_null($article)) {
            $this->sessionRepository->setErrors(['他のユーザーの投稿は編集できません。']);
            return new Response(302, '', ['Location: /']);
        }

        $title = $req->post['title'];
        $body = $req->post['body'];
        $newCategoryIds = $req->post['categoryIds'];


        try {
            $article = new Article($this->articleId, $title, $body, $this->userId);
            $articleRepository->updateArticle($article);

            // カテゴリを更新
            $newCategories = [];
            foreach ($newCategoryIds as $categoryId) {
                $newCategories[] = new Category((int)$categoryId, $this->articleId);
            }
            $categoryRepository->updateCategories($this->articleId, $newCategories);
            return new Response(302, '', ['Location: /article/' . $this->articleId]);

        } catch (InvalidArgumentException $e) {
            $_SESSION['errors'] = [$e->getMessage()];
            return new Response(302, '', ['Location: /article/' . $this->articleId . '/edit']);
        }
    }
}
