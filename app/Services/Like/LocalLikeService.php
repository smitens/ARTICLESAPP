<?php

namespace ArticleApp\Services\Like;

use ArticleApp\Models\Like;
use ArticleApp\Repositories\Like\LikeRepository;
use Carbon\Carbon;
use Exception;

class LocalLikeService
{
    private LikeRepository $likeRepository;

    public function __construct(LikeRepository $likeRepository)
    {
        $this->likeRepository = $likeRepository;
    }

    public function likeArticle(int $articleId): void
    {
        try {
            $createdAt = Carbon::now();
            $this->likeRepository->save(new Like($articleId, 'article', $createdAt, null));
        } catch (Exception $e) {
            throw new Exception('Error liking article: ' . $e->getMessage());
        }
    }

    public function likeComment(int $commentId): void
    {
        try {
            $createdAt = Carbon::now();
            $this->likeRepository->save(new Like($commentId, 'comment', $createdAt, null));
        } catch (Exception $e) {
            throw new Exception('Error liking comment: ' . $e->getMessage());
        }
    }
}