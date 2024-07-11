<?php

namespace ArticleApp\Services\Article;

use ArticleApp\Repositories\Article\ArticleRepository;
use ArticleApp\Repositories\Like\LikeRepository;
use ArticleApp\Repositories\Comment\CommentRepository;
use ArticleApp\Exceptions\DeleteArticleException;
use Exception;

class LocalDeleteService implements DeleteService
{
    private ArticleRepository $articleRepository;
    private LikeRepository $likeRepository;
    private CommentRepository $commentRepository;

    public function __construct
    (
        ArticleRepository $articleRepository,
        LikeRepository $likeRepository,
        CommentRepository $commentRepository
    )
    {
        $this->articleRepository = $articleRepository;
        $this->likeRepository = $likeRepository;
        $this->commentRepository = $commentRepository;
    }

    public function deleteArticle(int $id): void
    {
        try {
            $this->likeRepository->delete($id, 'article');

            $comments = $this->commentRepository->getByArticleId($id);

            foreach ($comments as $comment) {
                $this->likeRepository->delete($comment->getId(), 'comment');
            }

            $this->commentRepository->deleteByArticleId($id);

            $this->articleRepository->delete($id);
        } catch (Exception $e) {
            throw new DeleteArticleException('Failed to delete article: ' . $e->getMessage());
        }
    }
}