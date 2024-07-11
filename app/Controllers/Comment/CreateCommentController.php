<?php

namespace ArticleApp\Controllers\Comment;

use ArticleApp\RedirectResponse;
use ArticleApp\Services\Comment\CreateCommentService;
use ArticleApp\Services\LogService;
use Exception;
use Symfony\Component\HttpFoundation\Request;
use Respect\Validation\Validator as v;

class CreateCommentController
{
    private CreateCommentService $createCommentService;
    private LogService $logger;

    public function __construct(CreateCommentService $createCommentService, LogService $logger)
    {
        $this->createCommentService = $createCommentService;
        $this->logger = $logger;
    }

    public function __invoke(Request $request, array $vars): RedirectResponse
    {
        $articleId = (int) $vars['id'];
        $author = $request->request->get('author');
        $content = $request->request->get('content');

        if (empty($author) || empty($content)) {
            $errorMessage = 'All fields are required.';
            $this->logger->log('error','Error creating comment: ' . $errorMessage);
            return new RedirectResponse("/article/{$articleId}?error=" . urlencode($errorMessage));
        }

        try {
            $this->createCommentService->create($articleId, $author, $content);
            $this->logger->log('info','Comment created successfully.');
            return new RedirectResponse("/article/{$articleId}");
        } catch (Exception $e) {
            $this->logger->log('error','Error creating comment: ' . $e->getMessage());
            return new RedirectResponse("/article/{$articleId}?error=" . urlencode($e->getMessage()));
        }
    }
}