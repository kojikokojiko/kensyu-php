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

        if ($method === 'GET') {
            if ($path === '/') {
                return new \App\Controller\TopPageController();
            }
            if ($path === '/error') {
                return new \App\Controller\ErrorController();
            }
            // Uncomment and add more GET routes here
//             if ($path === '/article') {
//                 return new \App\Controller\ArticleAction();
//             }
        } elseif ($method === 'POST') {
            // Uncomment and add more POST routes here
             if ($path === '/article') {
                 return new \App\Controller\CreateArticleController();
             }
        }
//        どれも通らなかったら404のエラーコントローラーを返す
        return new \App\Controller\ErrorController("404 Not Found", 404);

    }
}
?>
