<?php

namespace ArticleApp\Services\Like;

use ArticleApp\Repositories\Like\LikeRepository;
use Exception;

class LocalCountService implements CountService
{
    private LikeRepository $likeRepository;

    public function __construct(LikeRepository $likeRepository)
    {
        $this->likeRepository = $likeRepository;
    }

    public function getArticleLikesCount(int $articleId): int
    {
        try {
            return $this->likeRepository->count($articleId, 'article');
        } catch (Exception $e) {
            throw new Exception('Error getting article likes count: ' . $e->getMessage());
        }
    }

    public function getCommentLikesCount(int $commentId): int
    {
        try {
            return $this->likeRepository->count($commentId, 'comment');
        } catch (Exception $e) {
            throw new Exception('Error getting comment likes count: ' . $e->getMessage());
        }
    }
}