<?php
declare(strict_types=1);

namespace App\Model;

use InvalidArgumentException;

readonly class Category
{
    public function __construct(
        public ?int   $id,
        public string $name
    )
    {
    }

}

?>
