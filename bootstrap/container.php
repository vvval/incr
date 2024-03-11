<?php

declare(strict_types=1);

use App\Application\Controllers;
use App\Application\Services\TeamService;
use App\Domain\Repositories\TeamRepository as TeamRepositoryContract;
use App\Infrastructure\Http\HandleExceptionMiddleware;
use App\Infrastructure\Http\JsonStrategy;
use App\Infrastructure\Logging\StdoutLogger;
use App\Infrastructure\Repositories\Persistence;
use App\Infrastructure\Repositories\TeamRepository;
use Laminas\Diactoros\ResponseFactory;
use League\Container\DefinitionContainerInterface;
use League\Route\Strategy\AbstractStrategy;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Log\LoggerInterface;

/**
 * @var DefinitionContainerInterface $container
 */
//Binds
$container->addShared(Persistence::class);
$container->addShared(LoggerInterface::class, StdoutLogger::class);
$container->addShared(ResponseFactoryInterface::class, ResponseFactory::class);
$container->addShared(TeamRepositoryContract::class, TeamRepository::class)
    ->addArgument(Persistence::class);

$container->addShared(HandleExceptionMiddleware::class)
    ->addArgument(ResponseFactoryInterface::class)
    ->addArgument(LoggerInterface::class);

$container->addShared(AbstractStrategy::class, JsonStrategy::class)
    ->addArgument(HandleExceptionMiddleware::class)
    ->addArgument(ResponseFactoryInterface::class)
    ->addMethodCall('setContainer', [$container]);

$container->add(TeamService::class)
    ->addArgument(TeamRepositoryContract::class);

// Controllers
$container->addShared(Controllers\Admin::class)
    ->addArgument(TeamService::class);
$container->addShared(Controllers\Team::class)
    ->addArgument(TeamService::class);
$container->addShared(Controllers\User::class)
    ->addArgument(TeamService::class);
