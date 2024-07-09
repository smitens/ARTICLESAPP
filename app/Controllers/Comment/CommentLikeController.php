<?php
namespace ArticleApp\Controllers\Comment;

use ArticleApp\RedirectResponse;
use ArticleApp\Services\Like\LocalLikeService;
use ArticleApp\Services\LogService;
use Exception;
use Symfony\Component\HttpFoundation\Request;

class CommentLikeController
{
    private LocalLikeService $likeService;
    private LogService $logger;

    public function __construct(LocalLikeService $likeService, LogService $logger)
    {
        $this->likeService = $likeService;
        $this->logger = $logger;
    }

    public function __invoke(Request $request, array $vars): RedirectResponse
    {
        $commentId = (int) $vars['id'];
        $articleId = (int) $request->request->get('articleId');

        try {
            $this->likeService->likeComment($commentId);
            $this->logger->log('Comment Id no. ' . $commentId . ' liked successfully.');
            return new RedirectResponse("/article/{$articleId}#comment-{$commentId}");
        } catch (Exception $e) {
            $this->logger->log('Error liking comment Id no. ' . $commentId . ': ' . $e->getMessage());
            return new RedirectResponse("/article/{$articleId}?error=" . urlencode($e->getMessage()));
        }
    }
}