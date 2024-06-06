<?php

namespace App\Controller\Auth;

use App\Controller\ControllerInterface;
use App\Http\Request;
use App\Http\Response;
use App\Model\User;
use App\Repository\UserRepository;
use PDO;

class LoginController implements ControllerInterface
{

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
                $_SESSION['user_id'] = $user->id;
                $_SESSION['user_name'] = $user->name;

                return new Response(302, '', ['Location: /']);
            } else {
                $_SESSION['errors'] = ['Invalid email or password'];

                return new Response(302, '', ['Location: /login']);
            }
        } else {
            $_SESSION['errors'] = $errors;

            return new Response(302, '', ['Location: /login']);
        }
    }
}
