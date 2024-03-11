<?php

declare(strict_types=1);

namespace App\Domain\Models;

use LogicException;

class UserCounter
{
    public function __construct(
        public readonly UserId $userId,
        private int $value = 0,
    ) {
        if ($this->value < 0) {
            throw new LogicException('Counter should be positive');
        }
    }

    public function getValue(): int
    {
        return $this->value;
    }

    public function increment(): void
    {
        $this->value++;
    }
}
