<?php

declare(strict_types=1);

namespace App\Infrastructure\Logging;

use Psr\Log\LoggerInterface;
use Psr\Log\LoggerTrait;
use Stringable;

class StdoutLogger implements LoggerInterface
{
    use LoggerTrait;

    public function log($level, Stringable|string $message, array $context = []): void
    {
        $stdout = fopen('php://stdout', 'wb');

        fwrite($stdout, "[$level] $message\n{$this->context($context)}");
    }

    private function context(array $context): string
    {
        if (!$context) {
            return '';
        }

        return json_encode($context, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . "\n";
    }
}
