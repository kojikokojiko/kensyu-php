<?php
declare(strict_types=1);
namespace App\Repository;

use App\Http\Request;
use App\Http\Response;
use App\Model\Article;
use App\Model\User;
use PDO;

/**
 * Class RegisterController
 *
 * Controller for handling user registration.
 *
 * @package App\Controller
 */
class UserRepository implements RepositoryInterface {
    /**
     * @var PDO The PDO instance for database connection.
     */
    private $db;

    /**
     * UserRepository constructor.
     *
     * Initializes the repository with the given PDO instance.
     *
     * @param PDO $db The PDO instance for database connection.
     */
    public function __construct(PDO $db) {
        $this->db = $db;
    }

    public function createUser(User $user): int {
        $stmt = $this->db->prepare('INSERT INTO users (name, email, password) VALUES (:name, :email, :password) RETURNING id');
        $stmt->bindValue(':name', $user->name);
        $stmt->bindValue(':email', $user->email);
        $stmt->bindValue(':password', $user->password);
        $stmt->execute();

        return (int) $stmt->fetchColumn();
    }

    public function getUserByEmail(string $email): ?User {
        $stmt = $this->db->prepare('SELECT * FROM users WHERE email = :email');
        $stmt->bindValue(':email', $email);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            return new User(
                $row['id'],
                $row['name'],
                $row['email'],
                $row['password'],
            );
        }
        return null;
    }

    public function emailExists(string $email): bool {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM users WHERE email = :email");
        $stmt->bindValue(':email', $email);
        $stmt->execute();
        return $stmt->fetchColumn() > 0;
    }


}
