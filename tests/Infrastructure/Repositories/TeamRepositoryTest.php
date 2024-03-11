<?php

declare(strict_types=1);

namespace Tests\Infrastructure\Repositories;

use App\Domain\Exceptions\TeamAlreadyExistsException;
use App\Domain\Exceptions\TeamNotFoundException;
use App\Domain\Models\Team;
use App\Domain\Models\TeamId;
use App\Domain\Models\UserCounter;
use App\Domain\Models\UserId;
use App\Infrastructure\Repositories\Persistence;
use App\Infrastructure\Repositories\TeamRepository;
use PHPUnit\Framework\Attributes\DoesNotPerformAssertions;
use PHPUnit\Framework\TestCase;
use Throwable;

class TeamRepositoryTest extends TestCase
{
    private TeamRepository $repository;

    /**
     * @throws Throwable
     */
    protected function setUp(): void
    {
        $persistence = $this->createMock(Persistence::class);
        $persistence->method('read')->willReturn([]);

        $this->repository = new TeamRepository($persistence);
    }

    public function testTeamNotFound(): void
    {
        $this->expectException(TeamNotFoundException::class);

        $this->repository->getTeam(new TeamId(1));
    }

    /**
     * @throws Throwable
     */
    #[DoesNotPerformAssertions]
    public function testTeamAdded(): void
    {
        $this->addTeam();
    }

    /**
     * @throws Throwable
     */
    public function testTeamFound(): void
    {
        $addedTeam = $this->addTeam();
        $team = $this->repository->getTeam($addedTeam->teamId);

        $this->assertEquals($addedTeam->getCounters(), $team->getCounters());
    }

    /**
     * @throws Throwable
     */
    public function testTeamCanBeAddedOnce(): void
    {
        $this->expectException(TeamAlreadyExistsException::class);

        $addedTeam = $this->addTeam();
        $this->repository->addTeam(new Team($addedTeam->teamId));
    }

    public function testTeamNotUpdatedIfNotFound(): void
    {
        $this->expectException(TeamNotFoundException::class);

        $team = new Team(new TeamId(1), [
            new UserCounter(new UserId(2), 3),
        ]);
        $this->repository->updateTeam($team);
    }

    /**
     * @throws Throwable
     */
    public function testTeamUpdated(): void
    {
        $addedTeam = $this->addTeam();

        $addedTeam->incrementCounter(new UserId(2));
        $addedTeam->addCounter(new UserId(3));
        $this->repository->updateTeam($addedTeam);

        $team = $this->repository->getTeam($addedTeam->teamId);

        $this->assertEquals([
            new UserCounter(new UserId(2), 4),
            new UserCounter(new UserId(3), 0),
        ], $team->getCounters());
    }

    /**
     * @throws Throwable
     */
    #[DoesNotPerformAssertions]
    public function testTeamDeletedAlways(): void
    {
        $this->addTeam();
        $this->repository->deleteTeam(new TeamId(1));
        $this->repository->deleteTeam(new TeamId(2));
    }

    /**
     * @throws Throwable
     */
    private function addTeam(): Team
    {
        $teamId = new TeamId(1);
        $team = new Team($teamId, [
            new UserCounter(new UserId(2), 3),
        ]);

        $this->repository->addTeam($team);

        return $team;
    }
}
