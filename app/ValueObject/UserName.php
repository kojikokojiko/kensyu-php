<?php
declare(strict_types=1);

namespace App\ValueObject;

use InvalidArgumentException;

readonly class UserName
{
    public function __construct(
        public string $value
    ) {
        if (trim($value) === '') {
            throw new InvalidArgumentException("Name is required.");
        } elseif (strlen($value) < 2 || strlen($value) > 255) {
            throw new InvalidArgumentException("Name must be between 2 and 255 characters.");
        }
    }
}
