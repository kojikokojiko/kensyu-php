<?php
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
     * @var array The route definitions.
     */
    private static $routes = [
        'GET' => [
            '/' => [\App\Controller\HomeController::class, 'index'],
            '/article' => [\App\Controller\ArticleController::class, 'show'],
        ],
        'POST' => [
//            '/article' => [\App\Controller\ArticleController::class, 'store'],
        ]
    ];

    /**
     * Get the controller and method based on the HTTP method and URI.
     *
     * @param string $method The HTTP method.
     * @param string $uri The URI.
     * @return array|null An array with the controller object and method name, or null if no route is found.
     */
    public static function getControllerAndMethod(string $method, string $uri): ?array {
        $path = parse_url($uri, PHP_URL_PATH);

        if (isset(self::$routes[$method][$path])) {
            [$class, $controllerMethod] = self::$routes[$method][$path];
            $controller = new $class();
            return [$controller, $controllerMethod];
        }

        return null;
    }
}
?>
