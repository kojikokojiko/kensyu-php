<?php
declare(strict_types=1);
namespace App;

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
     * @return array|null An array with the controller object and method name, or null if no route is found.
     */
    public static function getControllerAndMethod(string $method, string $uri): ?array {
        $path = parse_url($uri, PHP_URL_PATH);

        if ($method === 'GET') {
            if ($path === '/') {
                $controller = new \App\Controller\HomeController();
                return [$controller, 'index'];
            }
//            if ($path === '/article') {
//                 $controller = new \App\Controller\ArticleController();
//                 return [$controller, 'show'];
//            }
        } elseif ($method === 'POST') {
            // Uncomment and add more POST routes here
            // if ($path === '/article') {
            //     $controller = new \App\Controller\ArticleController();
            //     return [$controller, 'store'];
            // }
        }

        return null;
    }
}
?>
