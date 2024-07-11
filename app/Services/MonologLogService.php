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
        $dateFormat = "Y-m-d H:i:s";
        $output = "[%datetime%] %message%\n";

        $formatter = new LineFormatter($output, $dateFormat, true, true);

        $this->logger = new Logger('article_app');

        $streamHandler = new StreamHandler(__DIR__ . '/../../articleapp.log', Logger::INFO);
        $streamHandler->setFormatter($formatter);
        $this->logger->pushHandler($streamHandler);
    }

    public function log(string $level, string $message, array $context = []): void
    {
        $this->logger->log($level, $message, $context);
    }
}