<?php

declare(strict_types=1);

namespace App\Domain\Models;

class Team
{
    /**
     * @param array<UserCounter> $userCounters
     */
    public function __construct(
        public readonly TeamId $teamId,
        private array $userCounters = [],
    ) {
    }

    public function getTotalCounter(): int
    {
        $totalCounter = 0;
        foreach ($this->userCounters as $userCounter) {
            $totalCounter += $userCounter->getValue();
        }

        return $totalCounter;
    }

    public function addCounter(UserId $userId): void
    {
        $currentCounter = $this->findUserCounter($userId);
        if ($currentCounter === null) {
            $this->userCounters[] = new UserCounter($userId);
        }
    }

    public function deleteCounter(UserId $userId): void
    {
        $this->userCounters = array_filter(
            $this->userCounters,
            static fn(UserCounter $userCounter) => !$userCounter->userId->equalsTo($userId),
        );
    }

    public function incrementCounter(UserId $userId): void
    {
        $currentCounter = $this->findUserCounter($userId);
        $currentCounter?->increment();
    }

    /**
     * @return array<UserCounter>
     */
    public function getCounters(): array
    {
        return $this->userCounters;
    }

    private function findUserCounter(UserId $userId): ?UserCounter
    {
        foreach ($this->userCounters as $userCounter) {
            if ($userCounter->userId->equalsTo($userId)) {
                return $userCounter;
            }
        }

        return null;
    }
}
