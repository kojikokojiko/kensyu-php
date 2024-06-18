<?php
declare(strict_types=1);
namespace App\Controller;
use App\Dto\ArticleDetailDto;
use App\Http\Request;
use App\Http\Response;
use App\Model\Category;
use App\Repository\ArticleDetailRepository;
use App\Repository\ArticleRepository;
use App\Repository\SessionRepository;
use App\Repository\ThumbnailRepository;
use App\Utils\FileManager;
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
        $articleDetailRepository = new ArticleDetailRepository($db);
        $thumbnailRepository = new ThumbnailRepository($db);

        $article = $articleDetailRepository->getArticleDetailByIdAndUserId($this->articleId, $this->userId);

        // 記事が存在し、かつログインユーザーのものであることを確認
        if (is_null($article)) {
            $this->sessionRepository->setErrors(['他のユーザーの投稿は編集できません。']);
            return new Response(302, '', ['Location: /']);
        }


        try {
            // トランザクションの開始
            $db->beginTransaction();
            // 記事の削除
            $rowsDeleted = $articleRepository->deleteArticle($this->articleId);
            if ($rowsDeleted <= 0) {
                // トランザクションのロールバック
                $db->rollBack();
                // If the article was not found or already deleted, return a 404 response.
                return new Response(404, "記事が見つからないか、既に削除されています。");
            }

            // サムネイルの削除
            $thumbnailRepository->deleteThumbnailByArticleId($this->articleId);
            FileManager::deleteFile($article->thumbnailPath);

            // トランザクションのコミット
            $db->commit();

            // If deletion is successful, redirect to the article list page.
            return new Response(302, '', ['Location: /']);
        } catch (\Exception $e) {
            // エラーが発生した場合、トランザクションのロールバック
            $db->rollBack();
            // エラーの設定
            $this->sessionRepository->setErrors(['記事の削除中にエラーが発生しました。']);
            return new Response(500, "Internal Server Error");
        }
    }
}
