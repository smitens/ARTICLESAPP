<?php

namespace ArticleApp\Controllers;

use ArticleApp\Services\Article\GetByIdService;
use Symfony\Component\HttpFoundation\Request;
use ArticleApp\Services\LogService;
use ArticleApp\Response;
use Exception;

class GetByIdController
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

            if (!$article) {
                $this->logger->log('Article' . $id . ' not found');
                return new Response('Article not found',[]);
            }
            $this->logger->log('Article Id no. ' . $id . ' displayed successfully.');
            return new Response('article.twig', ['article' => $article]);
        } catch (Exception $e) {
            $this->logger->log('Error displaying article Id no. ' . $id . ' : ' . $e->getMessage());
            return new Response('error.twig', ['error_message' => $e->getMessage()]);
        }
    }
}