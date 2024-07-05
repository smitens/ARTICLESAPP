<?php

namespace ArticleApp\Controllers;

use ArticleApp\Services\Article\GetByIdService;
use Symfony\Component\HttpFoundation\Request;
use ArticleApp\Services\LogService;
use ArticleApp\Response;
use Exception;

class UpdateFormController
{
    private GetByIdService $getByIdService;
    private LogService $logger;

    public function __construct(GetByIdService $getByIdService, LogService $logger)
    {
        $this->getByIdService = $getByIdService;
        $this->logger = $logger;
    }

    public function __invoke(Request $request, array $vars): Response
    {
        $id = (int) $vars['id'];
        try {
            $article = $this->getByIdService->getArticleById($id);
            $this->logger->log('Update form showed successfully for article no. ' . $id . '.');
            return new Response('updateform.twig', ['article' => $article]);
        } catch (Exception $e) {
            $this->logger->log('Error showing update form for article no. ' . $id . '.' . $e->getMessage());
            return new Response('error.twig', ['error_message' => $e->getMessage()]);
        }
    }
}
