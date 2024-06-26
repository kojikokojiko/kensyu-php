<?php
declare(strict_types=1);
namespace App;

use App\Controller\ErrorController;
use App\Http\Request;
use App\Repository\SessionRepository;
use Exception;

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


        // SessionRepositoryを初期化してセッションを開始
        $sessionRepository = new SessionRepository();
        $sessionRepository->startSession();

        // Routeクラスを初期化
        Route::init($sessionRepository);

        $req = new Request(
            $_SERVER['REQUEST_METHOD'],
            $_SERVER['REQUEST_URI'],
            $_GET,
            $_POST,
            $_FILES
        );

        $controller = Route::getController($req);
        $db = Database::getConnection();
        try {
            $res = $controller($req, $db);
            $res->send();
        } catch (Exception $e) {
            $errorController = new ErrorController('Internal Server Error', 500);
            $res = $errorController($req, $db);
            $res->send();
        }
    }
}
