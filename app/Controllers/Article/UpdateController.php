<?php

namespace ArticleApp\Controllers\Article;

use ArticleApp\RedirectResponse;
use ArticleApp\Services\Article\UpdateService;
use ArticleApp\Services\LogService;
use Exception;
use Symfony\Component\HttpFoundation\Request;

class UpdateController
{
    private UpdateService $updateService;
    private LogService $logger;

    public function __construct(UpdateService $updateService, LogService $logger)
    {
        $this->updateService = $updateService;
        $this->logger = $logger;
    }

    public function __invoke(Request $request, array $vars): RedirectResponse
    {
        $id = (int) $vars['id'];
        $author = $request->request->get('author');
        $title = $request->request->get('title');
        $content = $request->request->get('content');

        try {
            $this->updateService->updateArticle($id, $author, $title, $content);
            $this->logger->log('info','Article Id no. ' . $id . ' updated successfully.');
            return new RedirectResponse('/article/' . $id);
        } catch (Exception $e) {
            $this->logger->log('error','Error updating article Id no. ' . $id . ':' . $e->getMessage());
            return new RedirectResponse('/article/' . $id . '/edit?error=' . urlencode($e->getMessage()));
        }
    }
}