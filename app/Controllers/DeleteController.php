<?php

namespace ArticleApp\Controllers;

use ArticleApp\Services\Article\DeleteService;
use Symfony\Component\HttpFoundation\Request;
use ArticleApp\RedirectResponse;
use ArticleApp\Services\LogService;
use Exception;

class DeleteController
{
    private DeleteService $deleteService;
    private LogService $logger;

    public function __construct(DeleteService $deleteService, LogService $logger)
    {
        $this->deleteService = $deleteService;
        $this->logger = $logger;
    }

    public function __invoke(Request $request, $vars): RedirectResponse
    {
        $id = (int)$vars['id'];

        try {
            $this->deleteService->deleteArticle($id);
            $this->logger->log('Article Id no. ' . $id . ' deleted successfully.');
            return new RedirectResponse('/articles');
        } catch (Exception $e) {
            $this->logger->log('Error deleting article Id no. ' . $id . ' :' . $e->getMessage());
            return new RedirectResponse('/article/' . $id . '?error=' . urlencode($e->getMessage()));
        }
    }
}