<?php
declare(strict_types=1);

namespace App\Controller\Article;

use App\Controller\ControllerInterface;
use App\Http\Request;
use App\Http\Response;
use App\Model\Article;
use App\Repository\ArticleCategoryRepository;
use App\Repository\ArticleRepository;
use InvalidArgumentException;
use PDO;

class UpdateArticleController implements ControllerInterface
{
    private int $articleId;

    public function __construct(int $articleId)
    {
        $this->articleId = $articleId;
    }

    public function __invoke(Request $req, PDO $db): Response
    {

        $articleRepository = new ArticleRepository($db);
        $articleCategoryRepository = new ArticleCategoryRepository($db);

        $title = $req->post['title'];
        $body = $req->post['body'];
        $userId = $_SESSION['user_id']; // セッションからユーザーIDを取得
        $categoryIds = $req->post['categoryIds'];

        // ユーザーがログインしていることを確認
        if (!$userId) {
            $_SESSION['errors'] = ['ログインが必要です。'];
            return new Response(302, '', ['Location: /login']);
        }

        // 編集する記事がログインユーザーのものであるか確認
        $article = $articleRepository->getArticleById($this->articleId);
        if ($article === null || $article->userId !== $userId) {
            $_SESSION['errors'] = ['他のユーザーの投稿は編集できません。'];
            return new Response(302, '', ['Location: /']);
        }


        try {
            $article = new Article($this->articleId, $title, $body, $userId);
            $rowsUpdated = $articleRepository->updateArticle($article);
            $articleCategoryRepository->updateArticleCategories($this->articleId, $categoryIds);

            return new Response(302, '', ['Location: /article/' . $this->articleId]);

        } catch (InvalidArgumentException $e) {
            $_SESSION['errors'] = [$e->getMessage()];
            return new Response(302, '', ['Location: /article/' . $this->articleId . '/edit']);
        }
    }
}
