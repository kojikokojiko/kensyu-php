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

        try{
            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
            $user=new User(null,$name,$email,$hashedPassword);

            $userId = $userRepository->createUser($user);
            $_SESSION['user_id'] = $user->id;
            $_SESSION['user_name'] = $user->name;

            return new Response(302, '', ['Location: /']);
        }catch (InvalidArgumentException $e){
            $_SESSION['errors'] = [$e->getMessage()];
            return new Response(302, '', ['Location: /register']);
        }
    }
}
