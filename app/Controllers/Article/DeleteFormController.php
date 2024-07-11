<?php

namespace ArticleApp\Controllers\Article;

use ArticleApp\Response;
use ArticleApp\Services\Article\GetByIdService;
use ArticleApp\Services\LogService;
use Exception;
use Symfony\Component\HttpFoundation\Request;

class DeleteFormController
{
    private GetByIdService $getByIdService;
    private LogService $logger;

    public function __construct(GetByIdService $getByIdService, LogService $logger)
    {
        $this->getByIdService = $getByIdService;
        $this->logger = $logger;
    }

    public function __invoke(Request $request, $vars): Response
    {
        $id = (int)$vars['id'];

        try {
            $article = $this->getByIdService->getArticleById($id);
            $this->logger->log('info','Delete form showed successfully for article no. ' . $id . '.');
            return new Response('deleteform.twig', ['article' => $article]);
        } catch (Exception $e) {
            $this->logger->log
            ('error','Error showing delete form for article no. ' . $id . ' .' . $e->getMessage());
            return new Response('error.twig', ['error_message' => $e->getMessage()]);
        }
    }
}