<?php
declare(strict_types=1);
namespace App;

use App\Controller\ErrorController;
use App\Exceptions\RepositoryException;
use App\Http\Request;
use App\Http\Response;
use Exception;
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
            $res = $controller($req, $db);
            $res->send();
        } catch (Exception $e) {
            $errorController = new ErrorController('Internal Server Error:'.$e, 500);
            $res = $errorController($req, $db);
            $res->send();
        }
    }
}
