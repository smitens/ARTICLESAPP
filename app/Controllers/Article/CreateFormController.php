<?php

namespace ArticleApp\Controllers\Article;

use ArticleApp\Response;
use ArticleApp\Services\LogService;

class CreateFormController
{
    private LogService $logger;

    public function __construct(LogService $logger)
    {
        $this->logger = $logger;
    }

    public function __invoke(): Response
    {
        $this->logger->log('info','Create form showed successfully.');
        return new Response('createform.twig', []);
    }
}