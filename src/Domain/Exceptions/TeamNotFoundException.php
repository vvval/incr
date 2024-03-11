<?php

declare(strict_types=1);

namespace App\Domain\Exceptions;

use App\Domain\Models\TeamId;
use Exception;

class TeamNotFoundException extends Exception
{
    public function __construct(TeamId $teamId)
    {
        parent::__construct(sprintf('Team %d not found', $teamId->value));
    }
}
