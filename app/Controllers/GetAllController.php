<?php

namespace ArticleApp\Controllers;

use ArticleApp\Services\Article\GetAllService;
use Symfony\Component\HttpFoundation\Request;
use ArticleApp\Services\LogService;
use ArticleApp\Response;
use Exception;

class GetAllController
{
    private GetAllService $getAllService;
    private LogService $logger;

    public function __construct(GetAllService $getAllService,  LogService $logger)
    {
        $this->getAllService = $getAllService;
        $this->logger = $logger;
    }

    public function __invoke(Request $request): Response
    {
        try {
            $articles = $this->getAllService->getAllArticles();
            $this->logger->log('Articles displayed successfully.');
            return new Response('articles.twig', ['articles' => $articles]);
        } catch (Exception $e) {
            $this->logger->log('Error displaying articles: ' . $e->getMessage());
            return new Response('error.twig', ['error_message' => $e->getMessage()]);
        }
    }
}