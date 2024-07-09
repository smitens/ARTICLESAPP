<?php

namespace ArticleApp\Controllers;

use ArticleApp\Response;
use ArticleApp\Services\LogService;

class IndexController
{
    private LogService $logger;

    public function __construct(LogService $logger)
    {
        $this->logger = $logger;
    }

    public function __invoke(): Response
    {
        $this->logger->log('Visitor entered index page.');
        return new Response('index.twig', []);
    }
}