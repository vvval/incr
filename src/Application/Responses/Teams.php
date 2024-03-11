<?php

declare(strict_types=1);

namespace App\Application\Responses;

use App\Domain\Models\Team;
use JsonSerializable;
use OpenApi\Attributes as OA;

#[OA\Schema(properties: [
    new OA\Property(property: 'teams', type: 'array', items: new OA\Items(ref: '#/components/schemas/TeamTotalCounter')),
])]
readonly class Teams implements JsonSerializable
{
    /**
     * @param array<Team> $teams
     */
    public function __construct(
        private array $teams,
    ) {
    }

    public function jsonSerialize(): array
    {
        return [
            'teams' => array_map(
                static fn(Team $team) => new TeamTotalCounter($team),
                $this->teams,
            ),
        ];
    }
}
