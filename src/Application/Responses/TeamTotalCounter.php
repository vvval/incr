<?php

declare(strict_types=1);

namespace App\Application\Responses;

use App\Domain\Models\Team;
use JsonSerializable;
use OpenApi\Attributes as OA;

#[OA\Schema(properties: [
    new OA\Property('id', type: 'int', example: 24),
    new OA\Property('total_counter', type: 'int', example: 996),
])]
readonly class TeamTotalCounter implements JsonSerializable
{
    public function __construct(
        private Team $team,
    ) {
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->team->teamId->value,
            'total_counter' => $this->team->getTotalCounter(),
        ];
    }
}
