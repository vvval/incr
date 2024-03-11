<?php

declare(strict_types=1);

namespace App\Application\Responses;

use App\Domain\Models\Team;
use App\Domain\Models\UserCounter;
use JsonSerializable;
use OpenApi\Attributes as OA;

#[OA\Schema(properties: [
    new OA\Property('id', type: 'int', example: 24),
    new OA\Property('counters', type: 'array', items: new OA\Items(properties: [
        new OA\Property('id', type: 'int', example: 15),
        new OA\Property('counter', type: 'int', example: 116),
    ])),
])]
readonly class TeamCounters implements JsonSerializable
{
    public function __construct(
        private Team $team,
    ) {
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->team->teamId->value,
            'counters' => array_map(
                static fn(UserCounter $userCounter) => [
                    'id' => $userCounter->userId->value,
                    'counter' => $userCounter->getValue(),
                ],
                $this->team->getCounters(),
            ),
        ];
    }
}
