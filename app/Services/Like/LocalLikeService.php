<?php

namespace ArticleApp\Services\Like;

use ArticleApp\Models\ArticleLike;
use ArticleApp\Models\CommentLike;
use ArticleApp\Repositories\Like\Article\ArticleLikeRepository;
use ArticleApp\Repositories\Like\Comment\CommentLikeRepository;
use Carbon\Carbon;
use Exception;

class LocalLikeService
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

    public function likeArticle(int $articleId): void
    {
        try {
            $createdAt = Carbon::now();
            $this->articleLikeRepository->save(new ArticleLike($articleId, $createdAt, null));
        } catch (Exception $e) {
            throw new Exception('Error liking article: ' . $e->getMessage());
        }
    }

    public function likeComment(int $commentId): void
    {
        try {
            $createdAt = Carbon::now();
            $this->commentLikeRepository->save(new CommentLike($commentId, $createdAt, null));
        } catch (Exception $e) {
            throw new Exception('Error liking comment: ' . $e->getMessage());
        }
    }
}