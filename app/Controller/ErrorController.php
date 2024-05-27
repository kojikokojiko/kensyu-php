<?php
declare(strict_types=1);
namespace App\Controller;

use App\Http\Request;
use App\Http\Response;
use PDO;

/**
 * Class ErrorController
 *
 * Controller for handling error display.
 *
 * @package App\Controller
 */
class ErrorController implements ControllerInterface {
    /**
     * Invoke action for displaying the error page.
     *
     * This method returns the error page with any error messages stored in the session.
     *
     * @param Request $req
     * @param PDO $db
     * @return Response The HTTP response object.
     */
    public function __invoke(Request $req, PDO $db): Response {
        ob_start();
        include __DIR__ . '/../View/error.php';

        $body = ob_get_clean();
        return new Response(400, $body);
    }
}
