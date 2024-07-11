<?php

namespace ArticleApp\Services\Article;

use ArticleApp\Models\Article;
use ArticleApp\Exceptions\ArticleDisplayException;
use ArticleApp\Repositories\Article\ArticleRepository;
use ArticleApp\Repositories\Comment\CommentRepository;
use ArticleApp\Repositories\Like\LikeRepository;
use Exception;

class LocalGetByIdService implements GetByIdService
{
    private ArticleRepository $articleRepository;
    private CommentRepository $commentRepository;
    private LikeRepository $likeRepository;

    public function __construct(
        ArticleRepository $articleRepository,
        CommentRepository $commentRepository,
        LikeRepository $likeRepository
    ) {
        $this->articleRepository = $articleRepository;
        $this->commentRepository = $commentRepository;
        $this->likeRepository = $likeRepository;
    }

    public function getArticleWithComments(int $articleId): array
    {
        $article = $this->articleRepository->findById($articleId);

        if (!$article) {
            return ['article' => null, 'comments' => [], 'articleLikes' => 0, 'commentLikes' => []];
        }

        $comments = $this->commentRepository->getByArticleId($articleId);
        $articleLikeCount = $this->likeRepository->count($articleId, 'article');

        $commentLikes = [];
        foreach ($comments as $comment) {
            $commentLikes[$comment->getId()] = $this->likeRepository->count($comment->getId(), 'comment');
        }

        return [
            'article' => $article,
            'comments' => $comments,
            'articleLikes' => $articleLikeCount,
            'commentLikes' => $commentLikes
        ];
    }

    public function getArticleById(int $id): ?Article
    {
        try {
            return $this->articleRepository->findById($id);
        } catch (Exception $e) {
            throw new ArticleDisplayException('Failed to display: ' . $e->getMessage());
        }
    }
}