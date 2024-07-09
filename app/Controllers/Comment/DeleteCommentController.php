<?php

namespace ArticleApp\Controllers\Comment;

use ArticleApp\RedirectResponse;
use ArticleApp\Services\Comment\DeleteCommentService;
use ArticleApp\Services\LogService;
use Exception;
use Symfony\Component\HttpFoundation\Request;

class DeleteCommentController
{
    private DeleteCommentService $deleteCommentService;
    private LogService $logger;

    public function __construct(DeleteCommentService $deleteCommentService, LogService $logger)
    {
        $this->deleteCommentService = $deleteCommentService;
        $this->logger = $logger;
    }

    public function __invoke(Request $request, array $vars): RedirectResponse
    {
        $commentId = (int) $vars['id'];
        $articleId = (int) $request->request->get('articleId');

        if (empty($commentId)) {
            $errorMessage = 'Comment ID is required.';
            $this->logger->log('Error deleting comment: ' . $errorMessage);
            return new RedirectResponse('/articles?error=' . urlencode($errorMessage));
        }

        try {
            $this->deleteCommentService->deleteComment($commentId);
            $this->logger->log('Comment deleted successfully.');
            return new RedirectResponse("/article/{$articleId}");
        } catch (Exception $e) {
            $this->logger->log('Error deleting comment: ' . $e->getMessage());
            return new RedirectResponse('/articles?error=' . urlencode($e->getMessage()));
        }
    }
}