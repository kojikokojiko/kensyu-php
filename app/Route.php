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
     * Get the route based on the HTTP method and URI.
     *
     * @param string $method The HTTP method.
     * @param string $uri The URI.
     * @return array|null The route information or null if no route is found.
     */
    private static function getRoute(string $method, string $uri) {
        $routes = [
            'GET' => [
                '/' => [\App\Controller\HomeController::class, 'index'],
            ],
            'POST' => [
            ]
        ];

        $path = parse_url($uri, PHP_URL_PATH);

        return $routes[$method][$path] ?? null;
    }

    /**
     * Get the controller based on the HTTP method and URI.
     *
     * @param string $method The HTTP method.
     * @param string $uri The URI.
     * @return object|null The controller object or null if no controller is found.
     */
    public static function getController(string $method, string $uri) {
        $route = self::getRoute($method, $uri);

        if ($route) {
            [$class, $method] = $route;
            return new $class();
        }

        return null;
    }

    /**
     * Get the controller method based on the HTTP method and URI.
     *
     * @param string $method The HTTP method.
     * @param string $uri The URI.
     * @return string|null The controller method or null if no method is found.
     */
    public static function getControllerMethod(string $method, string $uri): ?string {
        $route = self::getRoute($method, $uri);

        return $route[1] ?? null;
    }
}
?>
