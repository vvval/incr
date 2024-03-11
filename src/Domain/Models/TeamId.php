<?php

declare(strict_types=1);

namespace App\Domain\Models;

readonly class TeamId
{
    public function __construct(
        public int $value,
    ) {
    }

    public function equalsTo(TeamId $other): bool
    {
        return $this->value === $other->value;
    }
}
