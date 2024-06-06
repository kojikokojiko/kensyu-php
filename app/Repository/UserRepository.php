<?php
declare(strict_types=1);

namespace App\Repository;

use App\Model\User;
use App\ValueObject\Email;
use App\ValueObject\Password;
use App\ValueObject\UserName;
use PDO;

class UserRepository implements RepositoryInterface
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function createUser(User $user): int
    {
        $stmt = $this->db->prepare('INSERT INTO users (name, email, password) VALUES (:name, :email, :password) RETURNING id');
        $stmt->bindValue(':name', $user->name->value);
        $stmt->bindValue(':email', $user->email->value);
        $stmt->bindValue(':password', $user->password->value);
        $stmt->execute();

        return (int)$stmt->fetchColumn();
    }

    public function getUserByEmail(Email $email): ?User
    {
        $stmt = $this->db->prepare('SELECT * FROM users WHERE email = :email');
        $stmt->bindValue(':email', $email->value);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            return new User(
                $row['id'],
                new UserName($row['name']),
                new Email($row['email']),
                new Password($row['password']),
            );
        }
        return null;
    }

    public function existsByEmail(Email $email): bool
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM users WHERE email = :email");
        $stmt->bindValue(':email', $email->value);
        $stmt->execute();
        return $stmt->fetchColumn() > 0;
    }
}
