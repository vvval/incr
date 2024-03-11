<?php

declare(strict_types=1);

namespace App\Infrastructure\Http;

use League\Route\Http\Exception\HttpExceptionInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Log\LoggerInterface;
use Throwable;

readonly class HandleExceptionMiddleware implements MiddlewareInterface
{
    public function __construct(
        private ResponseFactoryInterface $responseFactory,
        private LoggerInterface $logger,
    ) {
    }

    public function process(
        ServerRequestInterface $request,
        RequestHandlerInterface $handler,
    ): ResponseInterface {
        $response = $this->responseFactory->createResponse();

        try {
            return $handler->handle($request);
        } catch (HttpExceptionInterface $exception) {
            return $exception->buildJsonResponse($response);
        } catch (Throwable $exception) {
            $this->logger->error($exception->getMessage(), explode("\n", $exception->getTraceAsString()));

            $response->getBody()->write(json_encode([
                'status_code' => 500,
                'reason_phrase' => $exception->getMessage(),
            ]));

            $response = $response->withAddedHeader('content-type', 'application/json');
            return $response->withStatus(500, strtok($exception->getMessage(), "\n"));
        }
    }
}
