<?php
declare(strict_types=1);

namespace App\Controller\Auth;

use App\Controller\ControllerInterface;
use App\Http\Request;
use App\Http\Response;
use PDO;

/**
 * Class LogoutController
 *
 * Controller for handling user logout.
 *
 * @package App\Controller
 */
class LogoutController implements ControllerInterface
{
    /**
     * Invoke action for user logout.
     *
     * @param Request $req
     * @param PDO $db
     * @return Response The HTTP response object containing the rendered view.
     */
    public function __invoke(Request $req, PDO $db): Response
    {
        session_unset(); // セッション変数を全てクリア
        session_destroy(); // セッションを破棄

        return new Response(302, '', ['Location: /']);
    }
}
