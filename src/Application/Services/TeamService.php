<?php

declare(strict_types=1);

namespace App\Application\Services;

use App\Domain\Exceptions\TeamAlreadyExistsException;
use App\Domain\Exceptions\TeamNotFoundException;
use App\Domain\Models\Team;
use App\Domain\Models\TeamId;
use App\Domain\Models\UserId;
use App\Domain\Repositories\TeamRepository;

readonly class TeamService
{
    public function __construct(
        private TeamRepository $teamRepository,
    ) {
    }

    /**
     * @throws TeamNotFoundException
     */
    public function getTeam(TeamId $teamId): Team
    {
        return $this->teamRepository->getTeam($teamId);
    }

    /**
     * @return array<Team>
     */
    public function getTeams(): array
    {
        return $this->teamRepository->getTeams();
    }

    /**
     * @throws TeamNotFoundException
     */
    public function addCounter(TeamId $teamId, UserId $userId): void
    {
        $team = $this->teamRepository->getTeam($teamId);
        $team->addCounter($userId);

        $this->teamRepository->updateTeam($team);
    }

    /**
     * @throws TeamNotFoundException
     */
    public function deleteCounter(TeamId $teamId, UserId $userId): void
    {
        $team = $this->teamRepository->getTeam($teamId);
        $team->deleteCounter($userId);

        $this->teamRepository->updateTeam($team);
    }

    /**
     * @throws TeamNotFoundException
     */
    public function incrementCounter(TeamId $teamId, UserId $userId): void
    {
        $team = $this->teamRepository->getTeam($teamId);
        $team->incrementCounter($userId);

        $this->teamRepository->updateTeam($team);
    }

    /**
     * @throws TeamAlreadyExistsException
     */
    public function addTeam(TeamId $teamId): void
    {
        $team = new Team($teamId);
        $this->teamRepository->addTeam($team);
    }

    public function deleteTeam(TeamId $teamId): void
    {
        $this->teamRepository->deleteTeam($teamId);
    }
}
