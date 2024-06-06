<?php
declare(strict_types=1);

namespace App\Model;

use App\ValueObject\Email;
use App\ValueObject\Password;
use InvalidArgumentException;

/**
 * Class User
 *
 * Represents a user.
 *
 * @package App\Model
 */
readonly class User
{
    public function __construct(
        public ?int     $id,
        public string   $name,
        public Email    $email,
        public Password $password
    )
    {
        $errors = $this->validateName($this->name);
        if (!empty($errors)) {
            throw new InvalidArgumentException(implode(', ', $errors));
        }
    }

    public function toHashedPassword(): self
    {
        return new self(
            $this->id,
            $this->name,
            $this->email,
            new Password($this->password->hash())
        );
    }

    private function validateName(string $name): array
    {
        $errors = [];

        if (trim($name) === '') {
            $errors[] = "Name is required.";
        } elseif (strlen($name) < 2 || strlen($name) > 255) {
            $errors[] = "Name must be between 2 and 255 characters.";
        }

        return $errors;
    }
}
