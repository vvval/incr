<?php

declare(strict_types=1);

namespace App\Domain\Exceptions;

use App\Domain\Models\TeamId;
use Exception;

class TeamAlreadyExistsException extends Exception
{
    public function __construct(TeamId $teamId)
    {
        parent::__construct(sprintf('Team %d already exists', $teamId->value));
    }
}
