<?php
declare(strict_types=1);

namespace App\Controller;

use App\Http\Request;
use App\Http\Response;
use PDO;

/**
 * Class RedirectController
 *
 * Controller for handling redirects.
 *
 * @package App\Controller
 */
class RedirectController implements ControllerInterface
{
    private string $location;

    public function __construct(string $location)
    {
        $this->location = $location;
    }

    /**
     * Invoke action for handling redirects.
     *
     * @param Request $req The HTTP request object.
     * @param PDO $db The database connection object.
     * @return Response The HTTP response object.
     */
    public function __invoke(Request $req, PDO $db): Response
    {
        return new Response(302, '', ['Location: ' . $this->location]);
    }
}
