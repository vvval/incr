<?php

declare(strict_types=1);

namespace App\Application\Controllers;

use App\Application\Services\TeamService;
use App\Domain\Exceptions\TeamNotFoundException;
use App\Domain\Models\TeamId;
use App\Domain\Models\UserId;
use Laminas\Diactoros\Response\JsonResponse;
use League\Route\Http\Exception\NotFoundException;
use OpenApi\Attributes as OA;
use Psr\Http\Message\ServerRequestInterface;
use Throwable;

readonly class User
{
    public function __construct(
        private TeamService $teamService,
    ) {
    }

    /**
     * @throws Throwable
     */
    #[OA\Put(
        path: '/team/{teamId}/counters/{userId}',
        tags: ['user'],
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
    #[OA\Post(
        path: '/team/{teamId}/counters/{userId}',
        tags: ['user'],
        parameters: [
            new OA\Parameter(name: 'teamId', in: 'path', required: true, schema: new OA\Schema(type: 'integer'), example: 24),
            new OA\Parameter(name: 'userId', in: 'path', required: true, schema: new OA\Schema(type: 'integer'), example: 15),
        ]
    )]
    #[OA\Response(response: 200, description: 'Team counter incremented')]
    #[OA\Response(response: 404, description: 'Team not found')]
    public function incrementCounter(ServerRequestInterface $request): JsonResponse
    {
        $teamId = new TeamId((int)$request->getAttribute('teamId'));
        $userId = new UserId((int)$request->getAttribute('userId'));

        try {
            $this->teamService->incrementCounter($teamId, $userId);
        } catch (TeamNotFoundException $e) {
            throw new NotFoundException($e->getMessage(), $e);
        }

        return new JsonResponse([], 200);
    }
}
