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
}
?>
