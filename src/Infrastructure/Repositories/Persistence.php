<?php

declare(strict_types=1);

namespace App\Infrastructure\Repositories;

use App\Domain\Models\Team;
use App\Domain\Models\TeamId;
use App\Domain\Models\UserCounter;
use App\Domain\Models\UserId;

class Persistence
{
    private string $filename;

    public function __construct()
    {
        $this->filename = dirname(__DIR__, 3) . '/storage/teams.json';
    }

    public function read(): array
    {
        if (!file_exists($this->filename)) {
            return [];
        }

        $data = file_get_contents($this->filename);
        return array_map(
            static fn(array $team) => new Team(
                new TeamId($team['id']),
                array_map(
                    static fn(array $userCounter) => new UserCounter(
                        new UserId($userCounter['id']),
                        $userCounter['counter'],
                    ),
                    $team['counters'],
                ),
            ),
            json_decode($data, true),
        );
    }

    public function persist(array $teams): void
    {
        $data = array_map(static fn(Team $team) => [
            'id' => $team->teamId->value,
            'counters' => array_map(
                static fn(UserCounter $userCounter) => [
                    'id' => $userCounter->userId->value,
                    'counter' => $userCounter->getValue(),
                ],
                $team->getCounters(),
            ),
        ], $teams);

        file_put_contents($this->filename, json_encode($data));
    }
}
