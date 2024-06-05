<?php

namespace App\Controller\Auth;

use App\Controller\ControllerInterface;
use App\Http\Request;
use App\Http\Response;
use App\Model\User;
use App\Repository\UserRepository;
use InvalidArgumentException;
use PDO;

class RegisterController implements ControllerInterface
{

    /**
     * Invoke action for user registration.
     *
     * @param Request $req The HTTP request object.
     * @param PDO $db The database connection object.
     * @return Response The HTTP response object containing the rendered view.
     */
    public function __invoke(Request $req, PDO $db): Response
    {
        $userRepository = new UserRepository($db);
        $name = $req->post['name'];
        $email = $req->post['email'];
        $password = $req->post['password'];

        // メールアドレスの重複をチェック
        if ($userRepository->existsByEmail($email)) {
            $_SESSION['errors'] = ["Email already exists."];
            return new Response(302, '', ['Location: /register']);
        }

        try {
            // ユーザーを作成→ハッシュ化
            $user = (new User(null, $name, $email, $password))->toHashedPassword();

            $userId = $userRepository->createUser($user);
            $_SESSION['user_id'] = $userId;
            $_SESSION['user_name'] = $user->name;

            return new Response(302, '', ['Location: /']);
        } catch (InvalidArgumentException $e) {
            $_SESSION['errors'] = [$e->getMessage()];
            return new Response(302, '', ['Location: /register']);
        }
    }
}
