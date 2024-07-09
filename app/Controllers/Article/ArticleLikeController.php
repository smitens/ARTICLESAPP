<?php

namespace ArticleApp\Controllers\Article;

use ArticleApp\RedirectResponse;
use ArticleApp\Services\Like\LocalLikeService;
use ArticleApp\Services\LogService;
use Exception;
use Symfony\Component\HttpFoundation\Request;

class ArticleLikeController
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
        $articleId = (int) $vars['id'];

        try {
            $this->likeService->likeArticle($articleId);
            $this->logger->log('Article liked successfully.');
            return new RedirectResponse("/article/{$articleId}");
        } catch (Exception $e) {
            $this->logger->log('Error liking article: ' . $e->getMessage());
            return new RedirectResponse("/article/{$articleId}?error=" . urlencode($e->getMessage()));
        }
    }
}