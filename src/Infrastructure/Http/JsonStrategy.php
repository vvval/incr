<?php

declare(strict_types=1);

namespace App\Infrastructure\Http;

use League\Route\Strategy\JsonStrategy as LeagueJsonStrategy;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Server\MiddlewareInterface;

class JsonStrategy extends LeagueJsonStrategy
{
    public function __construct(
        private readonly HandleExceptionMiddleware $handleExceptionMiddleware,
        ResponseFactoryInterface $responseFactory,
        int $jsonFlags = 0,
    ) {
        parent::__construct($responseFactory, $jsonFlags);
    }

    public function getThrowableHandler(): MiddlewareInterface
    {
        return $this->handleExceptionMiddleware;
    }
}
