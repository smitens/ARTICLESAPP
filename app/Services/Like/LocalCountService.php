<?php

namespace ArticleApp\Services\Like;

use ArticleApp\Repositories\Like\Article\ArticleLikeRepository;
use ArticleApp\Repositories\Like\Comment\CommentLikeRepository;
use Exception;

class LocalCountService implements CountService
{
    private ArticleLikeRepository $articleLikeRepository;
    private CommentLikeRepository $commentLikeRepository;

    public function __construct
    (
        ArticleLikeRepository $articleLikeRepository,
        CommentLikeRepository $commentLikeRepository
    )
    {
        $this->articleLikeRepository = $articleLikeRepository;
        $this->commentLikeRepository = $commentLikeRepository;
    }

    public function getArticleLikesCount(int $articleId): int
    {
        try {
            return $this->articleLikeRepository->countLikesForArticle($articleId);
        } catch (Exception $e) {
            throw new Exception('Error getting article likes count: ' . $e->getMessage());
        }
    }

    public function getCommentLikesCount(int $commentId): int
    {
        try {
            return $this->commentLikeRepository->countLikesForComment($commentId);
        } catch (Exception $e) {
            throw new Exception('Error getting comment likes count: ' . $e->getMessage());
        }
    }
}