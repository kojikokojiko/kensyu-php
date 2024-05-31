<?php
declare(strict_types=1);
namespace App;

use App\Controller\ControllerInterface;
use App\Http\Request;

/**
 * Class Route
 *
 * Handles the routing of the application.
 *
 * @package App
 */
class Route {
    /**
     * Get the controller and method based on the HTTP method and URI.
     *
     * @param string $method The HTTP method.
     * @param string $uri The URI.
     * @return ControllerInterface|null The controller object, or null if no route is found.
     */
    public static function getController(Request $req): ?ControllerInterface {
        $method = $req->method;
        $uri = $req->uri;
        $path = parse_url($uri, PHP_URL_PATH);

        // Check for method override
        if ($method === 'POST' && isset($req->post['_method'])) {
            $method = strtoupper($req->post['_method']);
        }

        if ($method === 'GET') {
            if ($path === '/') {
                return new Controller\Article\TopPageController();
            }
            if ($path === '/error') {
                return new \App\Controller\ErrorController();
            }
            if ($path === '/register') {
                return new Controller\Auth\RegisterPageController();
            }
            // Handle routes like /article/{id}
            if (preg_match('#^/article/(\d+)$#', $path, $matches)) {
                $articleId = (int) $matches[1];
                return new Controller\Article\ArticleDetailController($articleId);
            }
            // Handle routes like /article/{id}/edit
            if (preg_match('#^/article/(\d+)/edit$#', $path, $matches)) {
                $articleId = (int) $matches[1];
                return new Controller\Article\EditPageController($articleId);
            }
            if ($path === '/login') {
                return new \App\Controller\Auth\LoginPageController();
            }
        } elseif ($method === 'POST') {
            // Uncomment and add more POST routes here
             if ($path === '/article') {
                 return new Controller\Article\CreateArticleController();
             }
            if ($path === '/register') {
                return new Controller\Auth\RegisterController();
            }
            if ($path === '/login') {
                return new \App\Controller\Auth\LoginController();
            }
            if ($path === '/logout') {
                return new \App\Controller\Auth\LogoutController();
            }
        }elseif($method === 'DELETE') {
            // Handle routes like /article/{id}
            if (preg_match('#^/article/(\d+)$#', $path, $matches)) {
                $articleId = (int) $matches[1];
                return new Controller\Article\DeleteArticleController($articleId);
            }
        }
        elseif($method === 'PUT') {
            // Handle routes like /article/{id}
            if (preg_match('#^/article/(\d+)$#', $path, $matches)) {
                $articleId = (int) $matches[1];
                return new Controller\Article\UpdateArticleController($articleId);
            }
        }

//        どれも通らなかったら404のエラーコントローラーを返す
        return new \App\Controller\ErrorController("404 Not Found", 404);

    }
}
?>
