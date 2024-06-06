<?php
declare(strict_types=1);

namespace App\ValueObject;

use InvalidArgumentException;

readonly class Email
{
    public function __construct(
        public string $value
    )
    {
        if (trim($value) === '' || !filter_var($value, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException('Invalid email format');
        }
    }
}
