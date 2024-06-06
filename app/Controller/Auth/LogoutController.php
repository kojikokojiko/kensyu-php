<?php
declare(strict_types=1);

namespace App\Controller\Auth;

use App\Controller\ControllerInterface;
use App\Http\Request;
use App\Http\Response;
use App\Repository\SessionRepository;
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

    private SessionRepository $sessionRepository;

    public function __construct(SessionRepository $sessionRepository)
    {
        $this->sessionRepository = $sessionRepository;
    }

    /**
     * Invoke action for user logout.
     *
     * @param Request $req
     * @param PDO $db
     * @return Response The HTTP response object containing the rendered view.
     */
    public function __invoke(Request $req, PDO $db): Response
    {
        $this->sessionRepository->destroySession();

        return new Response(302, '', ['Location: /']);
    }
}
