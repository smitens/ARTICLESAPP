<?php

namespace ArticleApp\Services\Comment;

use ArticleApp\Repositories\Comment\CommentRepository;
use ArticleApp\Repositories\Like\Comment\SqliteCommentLikeRepository;
use ArticleApp\Exceptions\DeleteCommentException;
use Exception;

class LocalDeleteCommentService implements DeleteCommentService
{
    private CommentRepository $commentRepository;
    private SqliteCommentLikeRepository $sqliteCommentLikeRepository;

    public function __construct
    (
        CommentRepository $commentRepository,
        SqliteCommentLikeRepository $sqliteCommentLikeRepository
    )
    {
        $this->commentRepository = $commentRepository;
        $this->sqliteCommentLikeRepository = $sqliteCommentLikeRepository;

    }

    public function deleteByArticleId(int $articleId): void
    {
        try{
        $this->sqliteCommentLikeRepository->deleteCommentLikesByArticleId($articleId);
        $this->commentRepository->deleteByArticleId($articleId);
        } catch (Exception $e) {
            throw new DeleteCommentException('Failed to delete comments by articleId: ' . $e->getMessage());
        }
    }

    public function deleteComment(int $id): void
    {
        try{
            $this->sqliteCommentLikeRepository->deleteByCommentId($id);

            $this->commentRepository->delete($id);
        } catch (Exception $e) {
            throw new DeleteCommentException('Failed to delete comment: ' . $e->getMessage());
        }
    }
}