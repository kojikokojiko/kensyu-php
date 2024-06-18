<?php
declare(strict_types=1);
namespace App\Controller;
use App\Http\Request;
use App\Http\Response;
use App\Model\Article;
use App\Model\Category;
use App\Repository\ArticleImageRepository;
use App\Repository\ArticleRepository;
use App\Repository\CategoryRepository;
use App\Repository\SessionRepository;
use App\Repository\ThumbnailRepository;
use App\Utils\FileManager;
use Exception;
use PDO;

/**
 * Class CreateArticleController
 *
 * Controller for handling the creation of new articles.
 *
 * @package App\Controller
 */
class CreateArticleController implements ControllerInterface {


    private SessionRepository $sessionRepository;

    public function __construct(SessionRepository $sessionRepository)
    {
        $this->sessionRepository = $sessionRepository;
    }
    /**
     * Invoke action for creating a new article.
     *
     * This method processes the request to create a new article, interacts with the repository to save it,
     * and returns the appropriate response.
     *
     * @param Request $req The HTTP request object.
     * @param PDO $db The database connection object.
     * @return Response The HTTP response object.
     */
    public function __invoke(Request $req, PDO $db): Response {
        $articleRepository = new ArticleRepository($db);
        $categoryRepository = new CategoryRepository($db);
        $thumbnailRepository = new ThumbnailRepository($db);
        $articleImageRepository= new ArticleImageRepository($db);

        $title = $req->post['title'];
        $body = $req->post['body'];
        $categoryIds = $req->post['categoryIds'] ?? []; // カテゴリIDの配列を取得
        $thumbnail = $req->files['thumbnails']; // ファイルを取得
        $images = $req->files['article_images']; // 複数画像ファイルを取得

        $userId=$this->sessionRepository->get('user_id');
        // ユーザーがログインしていることを確認
        if (is_null($userId)) {
            $_SESSION['errors'] = ['ログインが必要です。'];
            $this->sessionRepository->setErrors(['ログインが必要です。']);
            return new Response(302, '', ['Location: /login']);
        }

        try{
            // トランザクションを開始
            $db->beginTransaction();

            $article= new Article(null, $title, $body, $userId);
            $articleId = $articleRepository->createArticle($article);
            $thumbnailPath = FileManager::saveFile($thumbnail, 'thumbnails');
            // サムネイル情報の保存
            $thumbnailRepository->createThumbnail($articleId, $thumbnailPath);

            // 複数画像の保存
            $imagePaths = [];
            foreach ($images['tmp_name'] as $index => $tmpName) {
                $image = [
                    'name' => $images['name'][$index],
                    'full_path' => $images['full_path'][$index],
                    'type' => $images['type'][$index],
                    'tmp_name' => $images['tmp_name'][$index],
                    'error' => $images['error'][$index],
                    'size' => $images['size'][$index]
                ];

                $imagePath = FileManager::saveFile($image, 'article_images');
                $imagePaths[] = $imagePath;
            }
            $articleImageRepository->createImages($articleId, $imagePaths);

            // カテゴリの保存
            if (!empty($categoryIds)) {
                $categories = [];
                foreach ($categoryIds as $categoryId) {
                    $categories[] = new Category((int)$categoryId, $articleId); // キャストして整数にする
                }
                $categoryRepository->insertBulk($categories);
            }

            // トランザクションをコミット
            $db->commit();

            return new Response(302, '', ['Location: /']);
        }catch (Exception $e){
            $db->rollBack();
            $_SESSION['errors'] = [$e->getMessage()];
            return new Response(302, '', ['Location: /']);
        }
    }
}
