<?php

declare(strict_types=1);

namespace App\Domain\Repositories;

use App\Domain\Exceptions\TeamAlreadyExistsException;
use App\Domain\Exceptions\TeamNotFoundException;
use App\Domain\Models\Team;
use App\Domain\Models\TeamId;

interface TeamRepository
{
    /**
     * @throws TeamNotFoundException
     */
    public function getTeam(TeamId $teamId): Team;

    /**
     * @throws TeamAlreadyExistsException
     */
    public function addTeam(Team $team): void;

    /**
     * @throws TeamNotFoundException
     */
    public function updateTeam(Team $team): void;

    public function deleteTeam(TeamId $teamId): void;

    /**
     * @return array<Team>
     */
    public function getTeams(): array;
}
