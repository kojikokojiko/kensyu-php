<?php
declare(strict_types=1);


namespace App\Controller\Auth;

use App\Controller\ControllerInterface;
use App\Http\Request;
use App\Http\Response;
use PDO;

class GetLoginPageController implements ControllerInterface
{
    public function __invoke(Request $req, PDO $db): Response
    {
        ob_start();
        include __DIR__ . '/../../View/login.php';
        $body = ob_get_clean();
        return new Response(200, $body);
    }
}
