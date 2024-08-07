<?php

namespace ArticleApp\Controllers\Article;

use ArticleApp\RedirectResponse;
use ArticleApp\Services\Article\DeleteService;
use ArticleApp\Services\LogService;
use Exception;
use Symfony\Component\HttpFoundation\Request;

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
            $this->logger->log('info','Article Id no. ' . $id . ' deleted successfully.');
            return new RedirectResponse('/articles');
        } catch (Exception $e) {
            $this->logger->log('error','Error deleting article Id no. ' . $id . ' :' . $e->getMessage());
            return new RedirectResponse('/article/' . $id . '?error=' . urlencode($e->getMessage()));
        }
    }
}