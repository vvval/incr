<?php

declare(strict_types=1);

namespace App\Infrastructure\Repositories;

use App\Domain\Exceptions\TeamAlreadyExistsException;
use App\Domain\Exceptions\TeamNotFoundException;
use App\Domain\Models\Team;
use App\Domain\Models\TeamId;
use App\Domain\Repositories\TeamRepository as TeamRepositoryContract;

class TeamRepository implements TeamRepositoryContract
{
    /** @var array<Team> */
    private array $teams;

    public function __construct(
        private readonly Persistence $persistence,
    ) {
        $this->teams = $this->persistence->read();
    }

    /**
     * @throws TeamNotFoundException
     */
    public function getTeam(TeamId $teamId): Team
    {
        return $this->findTeam($teamId) ?? throw new TeamNotFoundException($teamId);
    }

    /**
     * @throws TeamAlreadyExistsException
     */
    public function addTeam(Team $team): void
    {
        if ($this->findTeam($team->teamId) !== null) {
            throw new TeamAlreadyExistsException($team->teamId);
        }

        $this->teams[] = $team;

        $this->persist();
    }

    /**
     * @throws TeamNotFoundException
     */
    public function updateTeam(Team $team): void
    {
        if ($this->findTeam($team->teamId) === null) {
            throw new TeamNotFoundException($team->teamId);
        }

        $this->deleteTeam($team->teamId);
        $this->teams[] = $team;

        $this->persist();
    }

    public function deleteTeam(TeamId $teamId): void
    {
        $this->teams = array_filter(
            $this->teams,
            static fn(Team $team) => !$team->teamId->equalsTo($teamId),
        );

        $this->persist();
    }

    /**
     * @return array<Team>
     */
    public function getTeams(): array
    {
        return $this->teams;
    }

    private function persist(): void
    {
        $this->persistence->persist($this->teams);
    }

    private function findTeam(TeamId $teamId): ?Team
    {
        foreach ($this->teams as $team) {
            if ($team->teamId->equalsTo($teamId)) {
                return $team;
            }
        }

        return null;
    }
}
