<?php
declare(strict_types=1);
namespace App;

use App\Controller\ErrorController;
use App\Exceptions\RepositoryException;
use App\Http\Request;
use App\Http\Response;
use PDO;

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
        // PHPセッション設定の変更
        ini_set('session.cookie_samesite', 'Lax');
        //ini_set('session.cookie_secure', 'true'); // HTTPSの場合のみ必要
        ini_set('session.cookie_httponly', 'true');

        session_start();

        $req = new Request(
            $_SERVER['REQUEST_METHOD'],
            $_SERVER['REQUEST_URI'],
            $_GET,
            $_POST
        );

        $controller = Route::getController($req);
        $db = Database::getConnection();

        try {
            $this->handleController($controller, $req, $db);
        } catch (RepositoryException $e) {
            $this->handleError($e, $req, $db);
        }
    }

    /**
     * Handle the given controller.
     *
     * This method invokes the controller and sends the response.
     *
     * @param callable $controller The controller to handle.
     * @param Request $req The HTTP request object.
     * @param PDO $db The database connection object.
     * @return void
     */
    private function handleController($controller, Request $req, $db): void {
        if ($controller) {
            $res = $controller($req, $db);

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

    /**
     * Handle an error.
     *
     * This method handles the error, sets the error message in the session, and sends the error response.
     *
     * @param \Exception $e The exception that was caught.
     * @param Request $req The HTTP request object.
     * @param PDO $db The database connection object.
     * @return void
     */
    private function handleError(\Exception $e, Request $req, $db): void {
        $_SESSION['errors'] = [$e->getMessage()];
        $errorController = new ErrorController();
        $res = $errorController($req, $db);

        if ($res instanceof Response) {
            $res->send();
        } else {
            http_response_code(500);
            echo "An error occurred: " . $e->getMessage();
        }
    }
}
