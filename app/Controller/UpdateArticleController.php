<?php
declare(strict_types=1);
namespace App\Controller;

use App\Http\Request;
use App\Http\Response;
use App\Model\Article;
use App\Repository\ArticleRepository;
use InvalidArgumentException;
use PDO;

class UpdateArticleController implements ControllerInterface {
    private int $articleId;

    public function __construct(int $articleId) {
        $this->articleId = $articleId;
    }

    public function __invoke(Request $req, PDO $db): Response {
        $articleRepository = new ArticleRepository($db);
        $title = $req->post['title'];
        $body = $req->post['body'];

        try{
            $article= new Article(null,$title, $body);
            $rowsUpdated = $articleRepository->updateArticle($title, $body, $this->articleId);
            return new Response(302, '',['Location: /article/' . $this->articleId]);

        }catch (InvalidArgumentException $e){
            $_SESSION['errors'] = [$e->getMessage()];
            return new Response(302, '', ['Location: /article/' . $this->articleId.'/edit']);
        }
    }
}
