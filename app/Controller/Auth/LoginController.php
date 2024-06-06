<?php

namespace App\Controller\Auth;

use App\Controller\ControllerInterface;
use App\Http\Request;
use App\Http\Response;
use App\Model\User;
use App\Repository\SessionRepository;
use App\Repository\UserRepository;
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

        $email = trim($req->post['email']);
        $password = trim($req->post['password']);

        $errors = array_merge(
            User::validateEmail($email),
            User::validatePassword($password)
        );

        if (empty($errors)) {
            $user = $userRepository->getUserByEmail($email);
            if (!is_null($user) && password_verify($password, $user->password)) {
                // セッションIDの再生成
                $this->sessionRepository->regenerateSession();
                $this->sessionRepository->set('user_id', $user->id);
                $this->sessionRepository->set('user_name', $user->name);

                return new Response(302, '', ['Location: /']);
            } else {
                $this->sessionRepository->setErrors(['Invalid email or password']);

                return new Response(302, '', ['Location: /login']);
            }
        } else {
            $this->sessionRepository->setErrors($errors);

            return new Response(302, '', ['Location: /login']);
        }
    }
}
