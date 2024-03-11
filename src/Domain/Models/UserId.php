<?php

declare(strict_types=1);

namespace App\Domain\Models;

readonly class UserId
{
    public function __construct(
        public int $value,
    ) {
    }

    public function equalsTo(UserId $other): bool
    {
        return $this->value === $other->value;
    }
}
