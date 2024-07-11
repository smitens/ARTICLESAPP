<?php

namespace ArticleApp\Services\Comment;

use ArticleApp\Repositories\Comment\CommentRepository;
use ArticleApp\Repositories\Like\SqliteLikeRepository;
use ArticleApp\Exceptions\DeleteCommentException;
use Exception;
use PDO;


class LocalDeleteCommentService implements DeleteCommentService
{
    private CommentRepository $commentRepository;
    private SqliteLikeRepository $likeRepository;
    private PDO $db;

    public function __construct(
        CommentRepository $commentRepository,
        SqliteLikeRepository $likeRepository,
        PDO $db
    ) {
        $this->commentRepository = $commentRepository;
        $this->likeRepository = $likeRepository;
        $this->db = $db;
    }

    public function deleteByArticleId(int $articleId): void
    {
        try {
            $comments = $this->commentRepository->getByArticleId($articleId);

            foreach ($comments as $comment) {
                $this->deleteCommentLikes($comment->getId());
            }

            $this->commentRepository->deleteByArticleId($articleId);

        } catch (Exception $e) {
            throw new DeleteCommentException('Failed to delete comments by articleId: ' . $e->getMessage());
        }
    }

    public function delete(int $id): void
    {
        try {
            $this->deleteCommentLikes($id);

            $this->commentRepository->delete($id);

        } catch (Exception $e) {
            throw new DeleteCommentException('Failed to delete comment: ' . $e->getMessage());
        }
    }

    private function deleteCommentLikes(int $commentId): void
    {
        try {
            $this->likeRepository->delete($commentId, 'comment');

            $updateStmt = $this->db->prepare('UPDATE comments SET likeCount = 0 WHERE id = :commentId');
            $updateStmt->execute(['commentId' => $commentId]);

        } catch (Exception $e) {
            throw new DeleteCommentException('Failed to delete comment likes: ' . $e->getMessage());
        }
    }
}