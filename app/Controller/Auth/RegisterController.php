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

class RegisterController implements ControllerInterface
{
    private SessionRepository $sessionRepository;

    public function __construct(SessionRepository $sessionRepository)
    {
        $this->sessionRepository = $sessionRepository;
    }

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
        $name = trim($req->post['name']);
        $emailInput = trim($req->post['email']);
        $passwordInput = trim($req->post['password']);

        try {
            // バリューオブジェクトのバリデーション
            $email = new Email($emailInput);
            $password = new Password($passwordInput);

            // メールアドレスの重複をチェック
            if ($userRepository->existsByEmail($email)) {
                $_SESSION['errors'] = ["Email already exists."];
                return new Response(302, '', ['Location: /register']);
            }

            // ユーザーを作成→ハッシュ化
            $user = (new User(null, $name, $email, $password))->toHashedPassword();

            $userId = $userRepository->createUser($user);
            $this->sessionRepository->set('user_id', $userId);

            return new Response(302, '', ['Location: /']);
        } catch (InvalidArgumentException $e) {
            $_SESSION['errors'] = [$e->getMessage()];
            return new Response(302, '', ['Location: /register']);
        }
    }
}
