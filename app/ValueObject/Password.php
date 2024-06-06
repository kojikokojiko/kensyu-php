<?php
declare(strict_types=1);

namespace App\ValueObject;

use InvalidArgumentException;

readonly class Password
{
    public function __construct(
        public string $value
    )
    {
        if (strlen($value) < 8 ||
            !preg_match('/[A-Z]/', $value) ||
            !preg_match('/[a-z]/', $value) ||
            !preg_match('/[0-9]/', $value) ||
            !preg_match('/[^\w]/', $value)) {
            throw new InvalidArgumentException('Password must be at least 8 characters long and include a mix of uppercase letters, lowercase letters, numbers, and special characters.');
        }
    }

    public function hash(): string
    {
        return password_hash($this->value, PASSWORD_DEFAULT);
    }
}
