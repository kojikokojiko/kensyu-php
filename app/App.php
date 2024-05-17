<?php
declare(strict_types=1);

namespace App;

use App\Http\Request;
use App\Http\Response;

/**
 * Class App
 *
 * Main application class that handles the request and response cycle.
 *
 * @package App
 */
class App {
    /**
     * Run the application.
     *
     * This method initializes the request, determines the appropriate controller and method,
     * and sends the response.
     *
     * @return void
     */
    public function run(): void {
        $req = new Request(
            $_SERVER['REQUEST_METHOD'],
            $_SERVER['REQUEST_URI'],
            $_GET,
            $_POST
        );

        $route = Route::getControllerAndMethod($req->getMethod(), $req->getUri());

        if ($route) {
            [$controller, $method] = $route;
            $res = $controller->$method($req);

            if ($res instanceof Response) {
                $res->send();
            } else {
                http_response_code(500);
                echo "Invalid response from controller.";
            }
        } else {
            http_response_code(404);
            echo "Page not found.";
        }
    }
}
?>