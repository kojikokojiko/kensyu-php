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
                return new \App\Controller\TopPageController();
            }
            if ($path === '/error') {
                return new \App\Controller\ErrorController();
            }
            // Handle routes like /article/{id}
            if (preg_match('#^/article/(\d+)$#', $path, $matches)) {
                $articleId = (int) $matches[1];
                return new \App\Controller\ArticleDetailController($articleId);
            }
            // Handle routes like /article/{id}/edit
            if (preg_match('#^/article/(\d+)/edit$#', $path, $matches)) {
                $articleId = (int) $matches[1];
                return new \App\Controller\EditPageController($articleId);
            }
        } elseif ($method === 'POST') {
            // Uncomment and add more POST routes here
             if ($path === '/article') {
                 return new \App\Controller\CreateArticleController();
             }
        }elseif($method === 'DELETE') {
            // Handle routes like /article/{id}
            if (preg_match('#^/article/(\d+)$#', $path, $matches)) {
                $articleId = (int) $matches[1];
                return new \App\Controller\DeleteArticleController($articleId);
            }
        }

//        どれも通らなかったら404のエラーコントローラーを返す
        return new \App\Controller\ErrorController("404 Not Found", 404);

    }
}
?>
