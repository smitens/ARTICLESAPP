<?php

namespace ArticleApp\Controllers;

use ArticleApp\Services\Article\CreateService;
use Symfony\Component\HttpFoundation\Request;
use ArticleApp\RedirectResponse;
use ArticleApp\Services\LogService;
use Exception;

class CreateController
{
    private CreateService $createService;
    private LogService $logger;

    public function __construct(CreateService $createService, LogService $logger)
    {
        $this->createService = $createService;
        $this->logger = $logger;
    }

    public function __invoke(Request $request): RedirectResponse
    {
        $author = $request->request->get('author');
        $title = $request->request->get('title');
        $content = $request->request->get('content');


        if (empty($author) || empty($title) || empty($content)) {
            $errorMessage = 'All fields are required.';
            $this->logger->log('Error creating article: ' . $errorMessage);
            return new RedirectResponse('/article/create?error=' . urlencode($errorMessage));
        }

        try {
            $this->createService->createArticle($author, $title, $content);
            $this->logger->log('Article created successfully.');
            return new RedirectResponse('/articles');
        } catch (Exception $e) {
            $this->logger->log('Error creating article: ' . $e->getMessage());
            return new RedirectResponse('/article/create?error=' . urlencode($e->getMessage()));
        }
    }
}