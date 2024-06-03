<?php
declare(strict_types=1);
namespace App\Controller\Article;
use App\Controller\ControllerInterface;
use App\Http\Request;
use App\Http\Response;
use App\Repository\ArticleRepository;
use PDO;

/**
 * Class CreateArticleController
 *
 * Controller for handling the creation of new articles.
 *
 * @package App\Controller
 */
class EditPageController implements ControllerInterface {
    private int $articleId;

    public function __construct(int $articleId) {
        $this->articleId = $articleId;
    }
    /**
     * @param Request $req The HTTP request object.
     * @param PDO $db The database connection object.
     * @return Response The HTTP response object containing the rendered view.
     */
    public function __invoke(Request $req, PDO $db): Response {
        $articleRepository = new ArticleRepository($db);

        $article = $articleRepository->getArticleById($this->articleId);

        // ユーザーがログインしているかどうかをチェック
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['errors'] = ['ログインが必要です。'];
            return new Response(302, '', ['Location: /']);
        }

        // 記事が見つからない場合のエラーハンドリング
        if ($article === null) {
            $_SESSION['errors'] = ['記事が見つかりませんでした。'];
            return new Response(302, '', ['Location: /']);
        }

        // 編集する記事がログインユーザーのものであるか確認
        if ($article->userId !== $_SESSION['user_id']) {
            $_SESSION['errors'] = ['他のユーザーの投稿は編集できません。'];
            return new Response(302, '', ['Location: /']);
        }
        // 記事の編集ページを表示
        ob_start();
        include __DIR__ . '/../../View/edit_page.php';
        $body = ob_get_clean();
        return new Response(200, $body);
    }
}
