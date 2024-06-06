<?php

namespace App\Controller\Auth;

use App\Controller\ControllerInterface;
use App\Http\Request;
use App\Http\Response;
use App\Model\User;
use App\Repository\SessionRepository;
use App\Repository\UserRepository;
use App\ValueObject\Email;
use App\ValueObject\Password;
use InvalidArgumentException;
use PDO;

class LoginController implements ControllerInterface
{
    private SessionRepository $sessionRepository;

    public function __construct(SessionRepository $sessionRepository)
    {
        $this->sessionRepository = $sessionRepository;
    }


    /**
     * Invoke action for user login.
     *
     * @param Request $req The HTTP request object.
     * @param PDO $db The database connection object.
     * @return Response The HTTP response object containing the rendered view.
     */

    public function __invoke(Request $req, PDO $db): Response
    {
        $userRepository = new UserRepository($db);

        $emailInput = trim($req->post['email']);
        $passwordInput = trim($req->post['password']);

        try {
            $email = new Email($emailInput);
            $password = new Password($passwordInput);

            $user = $userRepository->getUserByEmail($email);
            if (!is_null($user) && password_verify($password->value, $user->password->value)) {
                // セッションIDの再生成
                $this->sessionRepository->regenerateSession();
                $this->sessionRepository->set('user_id', $user->id);

                return new Response(302, '', ['Location: /']);
            } else {
                $this->sessionRepository->setErrors(['Invalid email or password']);

                return new Response(302, '', ['Location: /login']);
            }
        } catch (InvalidArgumentException $e) {
            $this->sessionRepository->setErrors([$e->getMessage()]);

            return new Response(302, '', ['Location: /login']);
        }

    }
}
