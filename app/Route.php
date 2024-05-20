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
     * return callable|null The controller object, or null if no route is found.
     */
    public static function getController(Request $req): ?callable {
        $method = $req->method;
        $uri = $req->uri;
        $path = parse_url($uri, PHP_URL_PATH);
        $pathParts = explode('/', trim($path, '/'));

        // Check for method override
        if ($method === 'POST' && isset($req->post['_method'])) {
            $method = strtoupper($req->post['_method']);
        }

        if ($method === 'GET') {
            if ($path === '/') {
                return new \App\Controller\TopPageController();
            }
            // Handle routes like /article/{id}
            if (count($pathParts) === 2 && $pathParts[0] === 'article') {
                $articleId = (int) $pathParts[1];
                return new \App\Controller\ArticleDetailController($articleId);
            }
            // Handle routes like /article/{id}/edit
            if (count($pathParts) === 3 && $pathParts[0] === 'article' && $pathParts[2] === 'edit') {
                $articleId = (int) $pathParts[1];
                return new \App\Controller\EditPageController($articleId);
            }
        } elseif ($method === 'POST') {
            // Uncomment and add more POST routes here
             if ($path === '/article') {
                 return new \App\Controller\CreateArticleController();
             }
             // Handle routes like /article/{id}/edit
            if (count($pathParts) === 3 && $pathParts[0] === 'article' && $pathParts[2] === 'edit') {
                $articleId = (int) $pathParts[1];
                return new \App\Controller\UpdateArticleController($articleId);
            }
        }elseif ($method === 'DELETE') {
            // Handle routes like /article/{id}
            if (count($pathParts) === 2 && $pathParts[0] === 'article') {
                $articleId = (int) $pathParts[1];
                return new \App\Controller\DeleteArticleController($articleId);
            }
        }
        return null;
    }
}
?>
