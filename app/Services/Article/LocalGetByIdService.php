<?php
namespace ArticleApp\Services\Article;

use ArticleApp\Models\Article;
use ArticleApp\Exceptions\ArticleDisplayException;
use ArticleApp\Repositories\Article\ArticleRepository;
use ArticleApp\Repositories\Comment\CommentRepository;
use ArticleApp\Repositories\Like\Article\ArticleLikeRepository;
use ArticleApp\Repositories\Like\Comment\CommentLikeRepository;
use Exception;

class LocalGetByIdService implements GetByIdService
{
    private ArticleRepository $articleRepository;
    private CommentRepository $commentRepository;
    private ArticleLikeRepository $articleLikeRepository;
    private CommentLikeRepository $commentLikeRepository;

    public function __construct(
        ArticleRepository $articleRepository,
        CommentRepository $commentRepository,
        ArticleLikeRepository $articleLikeRepository,
        CommentLikeRepository $commentLikeRepository
    ) {
        $this->articleRepository = $articleRepository;
        $this->commentRepository = $commentRepository;
        $this->articleLikeRepository = $articleLikeRepository;
        $this->commentLikeRepository = $commentLikeRepository;
    }

    public function getArticleWithComments(int $articleId): array
    {
        $article = $this->articleRepository->findById($articleId);

        if (!$article) {
            return ['article' => null, 'comments' => [], 'articleLikes' => 0, 'commentLikes' => []];
        }

        $comments = $this->commentRepository->getByArticleId($articleId);
        $articleLikeCount = $this->articleLikeRepository->countLikesForArticle($articleId);

        $commentLikes = [];
        foreach ($comments as $comment) {
            $commentLikes[$comment->getId()] = $this->commentLikeRepository->countLikesForComment($comment->getId());
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