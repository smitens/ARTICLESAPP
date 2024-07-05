<?php

namespace ArticleApp\Services;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Formatter\LineFormatter;

class MonologLogService implements LogService
{
    private Logger $logger;

    public function __construct()
    {
        $formatter = new LineFormatter("%message%\n");
        $this->logger = new Logger('article_app');
        $this->logger->pushHandler(new StreamHandler(__DIR__ . '/../../articleapp.log', Logger::INFO));
        $handler = $this->logger->getHandlers()[0];
        $handler->setFormatter($formatter);
    }

    public function log(string $message): void
    {
        $this->logger->info($message);
    }
}