<?php

namespace ArticleApp\Services\Comment;

use ArticleApp\Repositories\Comment\CommentRepository;
use ArticleApp\Exceptions\CommentDisplayException;
use Exception;

class LocalGetByArticleIdService implements GetByArticleIdService
{
    private CommentRepository $commentRepository;

    public function __construct(CommentRepository $commentRepository)
    {
        $this->commentRepository = $commentRepository;
    }

    public function getCommentByArticleId(int $articleId): array
    {
        try {
        return $this->commentRepository->getByArticleId($articleId);
        } catch (Exception $e) {
            throw new CommentDisplayException('Failed to display: ' . $e->getMessage());
        }
    }
}