<?php

namespace ArticleApp\Controllers;

use ArticleApp\RedirectResponse;
use ArticleApp\Services\Like\LocalLikeService;
use ArticleApp\Services\LogService;
use Exception;
use Symfony\Component\HttpFoundation\Request;

class LikeController
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
        $id = (int) $vars['id'];
        $type = $request->request->get('type');
        $articleId = $request->request->get('articleId') ? (int) $request->request->get('articleId') : $id;

        try {
            if ($type === 'article') {
                $this->likeService->likeArticle($id);
                $this->logger->log('info','Article liked successfully.');
                return new RedirectResponse("/article/{$id}");
            } elseif ($type === 'comment') {
                $this->likeService->likeComment($id);
                $this->logger->log('info','Comment Id no. ' . $id . ' liked successfully.');
                return new RedirectResponse("/article/{$articleId}#comment-{$id}");
            } else {
                throw new Exception('Invalid like type.');
            }
        } catch (Exception $e) {
            $this->logger->log
            ('error','Error liking ' . $type . ' Id no. ' . $id . ': ' . $e->getMessage());
            return new RedirectResponse("/article/{$articleId}?error=" . urlencode($e->getMessage()));
        }
    }
}