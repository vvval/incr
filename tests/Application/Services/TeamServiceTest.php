<?php

declare(strict_types=1);

namespace Tests\Application\Services;

use App\Application\Services\TeamService;
use App\Domain\Models\TeamId;
use App\Domain\Models\UserCounter;
use App\Domain\Models\UserId;
use App\Infrastructure\Repositories\Persistence;
use App\Infrastructure\Repositories\TeamRepository;
use PHPUnit\Framework\TestCase;
use Throwable;

class TeamServiceTest extends TestCase
{
    private TeamService $service;

    /**
     * @throws Throwable
     */
    protected function setUp(): void
    {
        $persistence = $this->createMock(Persistence::class);
        $persistence->method('read')->willReturn([]);

        $this->service = new TeamService(new TeamRepository($persistence));
    }

    /**
     * @throws Throwable
     */
    public function testAddCounter(): void
    {
        $teamId = new TeamId(1);
        $userId = new UserId(2);

        $this->service->addTeam($teamId);
        $this->service->addCounter($teamId, $userId);

        $this->assertEquals([
            new UserCounter($userId, 0),
        ], $this->service->getTeam($teamId)->getCounters());
    }

    /**
     * @throws Throwable
     */
    public function testIncrementCounter(): void
    {
        $teamId = new TeamId(1);
        $userId = new UserId(2);

        $this->service->addTeam($teamId);
        $this->service->addCounter($teamId, $userId);
        $this->service->incrementCounter($teamId, $userId);

        $this->assertEquals([
            new UserCounter($userId, 1),
        ], $this->service->getTeam($teamId)->getCounters());
    }

    /**
     * @throws Throwable
     */
    public function testDeleteCounter(): void
    {
        $teamId = new TeamId(1);
        $userId = new UserId(2);

        $this->service->addTeam($teamId);
        $this->service->addCounter($teamId, $userId);
        $this->service->deleteCounter($teamId, $userId);

        $this->assertEmpty($this->service->getTeam($teamId)->getCounters());
    }
}
