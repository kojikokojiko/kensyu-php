<?php
declare(strict_types=1);
namespace App\Controller;
use App\Http\Request;
use App\Http\Response;
use App\Model\Article;
use App\Repository\ArticleRepository;
use App\Repository\SessionRepository;
use App\Repository\ThumbnailRepository;
use App\Utils\FileManager;
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
        $thumbnailRepository = new ThumbnailRepository($db);
        $article = $articleRepository->getArticleByIdAndUserId($this->articleId, $this->userId);

        // 記事が存在し、かつログインユーザーのものであることを確認
        if (is_null($article)) {
            $this->sessionRepository->setErrors(['他のユーザーの投稿は編集できません。']);
            return new Response(302, '', ['Location: /']);
        }

        // トランザクションの開始
        $db->beginTransaction();
        try {
            // 記事の削除
            $rowsDeleted = $articleRepository->deleteArticle($this->articleId);
            // サムネイルの削除
            $thumbnailRepository->deleteThumbnailByArticleId($this->articleId);
            FileManager::deleteFile($article->thumbnailPath);
            if ($rowsDeleted > 0) {
                // トランザクションのコミット
                $db->commit();
                // If deletion is successful, redirect to the article list page.
                return new Response(302, '', ['Location: /']);
            } else {
                // トランザクションのロールバック
                $db->rollBack();
                // If the article was not found or already deleted, return a 404 response.
                return new Response(404, "Article not found or already deleted");
            }
        } catch (\RunTimeException $e) {
            // エラーが発生した場合、トランザクションのロールバック
            $db->rollBack();
            // エラーの設定
            $this->sessionRepository->setErrors(['記事の削除中にエラーが発生しました。']);
            return new Response(500, "Internal Server Error");
        }
    }
}
