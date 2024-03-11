<?php

declare(strict_types=1);

namespace App;

use League\Container\Container;
use League\Route\Router;
use League\Route\Strategy\AbstractStrategy;
use OpenApi\Attributes as OA;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

#[OA\Info(version: '0.1', title: 'API')]
final class Application
{
    private ContainerInterface $container;
    private Router $router;

    public function __construct()
    {
        $this->container = $this->initializeContainer();
        $this->router = $this->initializeRouter();
    }

    public function container(): ContainerInterface
    {
        return $this->container;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        return $this->router->dispatch($request);
    }

    private function initializeContainer(): ContainerInterface
    {
        $container = new Container();

        require_once dirname(__DIR__) . '/bootstrap/container.php';

        return $container;
    }

    private function initializeRouter(): Router
    {
        $strategy = $this->container->get(AbstractStrategy::class);
        $router = new Router();
        $router->setStrategy($strategy);

        require_once dirname(__DIR__) . '/bootstrap/router.php';

        return $router;
    }
}
