<?php

declare(strict_types=1);

namespace App\Application\Controllers;

use App\Application\Responses\TeamCounters;
use App\Application\Responses\Teams as TeamsResponse;
use App\Application\Responses\TeamTotalCounter;
use App\Application\Services\TeamService;
use App\Domain\Exceptions\TeamNotFoundException;
use App\Domain\Models\TeamId;
use League\Route\Http\Exception\NotFoundException;
use OpenApi\Attributes as OA;
use Psr\Http\Message\ServerRequestInterface;
use Throwable;

readonly class Team
{
    public function __construct(
        private TeamService $teamService,
    ) {
    }

    #[OA\Get(
        path: '/teams',
        tags: ['team'],
    )]
    #[OA\Response(
        response: 200,
        description: 'All teams with their step counts',
        content: new OA\JsonContent(ref: '#/components/schemas/Teams'),
    )]
    public function get(): TeamsResponse
    {
        return new TeamsResponse($this->teamService->getTeams());
    }

    /**
     * @throws Throwable
     */
    #[OA\Get(
        path: '/team/{teamId}/counters/total',
        tags: ['team'],
        parameters: [
            new OA\Parameter(name: 'teamId', in: 'path', required: true, schema: new OA\Schema(type: 'integer'), example: 24),
        ]
    )]
    #[OA\Response(
        response: 200,
        description: 'Current total steps taken by a team',
        content: new OA\JsonContent(ref: '#/components/schemas/TeamTotalCounter'),
    )]
    #[OA\Response(response: 404, description: 'Team not found')]
    public function getTotalCounter(ServerRequestInterface $request): TeamTotalCounter
    {
        $teamId = new TeamId((int)$request->getAttribute('teamId'));

        try {
            $team = $this->teamService->getTeam($teamId);
        } catch (TeamNotFoundException $e) {
            throw new NotFoundException($e->getMessage(), $e);
        }

        return new TeamTotalCounter($team);
    }

    /**
     * @throws Throwable
     */
    #[OA\Get(
        path: '/team/{teamId}/counters',
        tags: ['team'],
        parameters: [
            new OA\Parameter(name: 'teamId', in: 'path', required: true, schema: new OA\Schema(type: 'integer'), example: 24),
        ]
    )]
    #[OA\Response(
        response: 200,
        description: 'All counters in a team',
        content: new OA\JsonContent(ref: '#/components/schemas/TeamCounters'),
    )]
    #[OA\Response(response: 404, description: 'Team not found')]
    public function getCounters(ServerRequestInterface $request): TeamCounters
    {
        $teamId = new TeamId((int)$request->getAttribute('teamId'));

        try {
            $team = $this->teamService->getTeam($teamId);
        } catch (TeamNotFoundException $e) {
            throw new NotFoundException($e->getMessage(), $e);
        }

        return new TeamCounters($team);
    }
}
