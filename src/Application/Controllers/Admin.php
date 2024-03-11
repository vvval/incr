<?php

declare(strict_types=1);

namespace App\Application\Controllers;

use App\Application\Services\TeamService;
use App\Domain\Exceptions\TeamAlreadyExistsException;
use App\Domain\Exceptions\TeamNotFoundException;
use App\Domain\Models\TeamId;
use App\Domain\Models\UserId;
use Laminas\Diactoros\Response\JsonResponse;
use League\Route\Http\Exception\ConflictException;
use League\Route\Http\Exception\NotFoundException;
use OpenApi\Attributes as OA;
use Psr\Http\Message\ServerRequestInterface;
use Throwable;

readonly class Admin
{
    public function __construct(
        private TeamService $teamService,
    ) {
    }

    /**
     * @throws Throwable
     */
    #[OA\Put(
        path: '/admin/team/{teamId}',
        tags: ['admin'],
        parameters: [
            new OA\Parameter(name: 'teamId', in: 'path', required: true, schema: new OA\Schema(type: 'integer'), example: 24),
        ],
    )]
    #[OA\Response(response: 200, description: 'Team added')]
    #[OA\Response(response: 409, description: 'Team already added')]
    public function addTeam(ServerRequestInterface $request): JsonResponse
    {
        $teamId = new TeamId((int)$request->getAttribute('teamId'));

        try {
            $this->teamService->addTeam($teamId);
        } catch (TeamAlreadyExistsException $e) {
            throw new ConflictException($e->getMessage(), $e);
        }

        return new JsonResponse([], 200);
    }

    #[OA\Delete(
        path: '/admin/team/{teamId}',
        tags: ['admin'],
        parameters: [
            new OA\Parameter(name: 'teamId', in: 'path', required: true, schema: new OA\Schema(type: 'integer'), example: 24),
        ]
    )]
    #[OA\Response(response: 200, description: 'Team deleted')]
    public function deleteTeam(ServerRequestInterface $request): JsonResponse
    {
        $teamId = new TeamId((int)$request->getAttribute('teamId'));
        $this->teamService->deleteTeam($teamId);

        return new JsonResponse([], 200);
    }

    /**
     * @throws Throwable
     */
    #[OA\Put(
        path: '/admin/team/{teamId}/counters/{userId}',
        tags: ['admin'],
        parameters: [
            new OA\Parameter(name: 'teamId', in: 'path', required: true, schema: new OA\Schema(type: 'integer'), example: 24),
            new OA\Parameter(name: 'userId', in: 'path', required: true, schema: new OA\Schema(type: 'integer'), example: 15),
        ]
    )]
    #[OA\Response(response: 200, description: 'Team counter added')]
    #[OA\Response(response: 404, description: 'Team not found')]
    public function addCounter(ServerRequestInterface $request): JsonResponse
    {
        $teamId = new TeamId((int)$request->getAttribute('teamId'));
        $userId = new UserId((int)$request->getAttribute('userId'));

        try {
            $this->teamService->addCounter($teamId, $userId);
        } catch (TeamNotFoundException $e) {
            throw new NotFoundException($e->getMessage(), $e);
        }

        return new JsonResponse([], 200);
    }

    /**
     * @throws Throwable
     */
    #[OA\Delete(
        path: '/admin/team/{teamId}/counters/{userId}',
        tags: ['admin'],
        parameters: [
            new OA\Parameter(name: 'teamId', in: 'path', required: true, schema: new OA\Schema(type: 'integer'), example: 24),
            new OA\Parameter(name: 'userId', in: 'path', required: true, schema: new OA\Schema(type: 'integer'), example: 15),
        ]
    )]
    #[OA\Response(response: 200, description: 'Team counter deleted')]
    #[OA\Response(response: 404, description: 'Team not found')]
    public function deleteCounter(ServerRequestInterface $request): JsonResponse
    {
        $teamId = new TeamId((int)$request->getAttribute('teamId'));
        $userId = new UserId((int)$request->getAttribute('userId'));

        try {
            $this->teamService->deleteCounter($teamId, $userId);
        } catch (TeamNotFoundException $e) {
            throw new NotFoundException($e->getMessage(), $e);
        }

        return new JsonResponse([], 200);
    }
}
