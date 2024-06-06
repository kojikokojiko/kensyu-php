<?php
declare(strict_types=1);

namespace App\Model;

use App\ValueObject\Email;
use App\ValueObject\Password;
use App\ValueObject\UserName;


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
        public UserName $name,
        public Email    $email,
        public Password $password
    )
    {
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
}
