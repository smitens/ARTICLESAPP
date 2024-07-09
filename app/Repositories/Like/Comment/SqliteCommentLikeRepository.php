<?php

namespace ArticleApp\Repositories\Like\Comment;

use PDO;
use ArticleApp\Models\CommentLike;
use Exception;

class SqliteCommentLikeRepository implements CommentLikeRepository
{
    private PDO $db;

    public function __construct(PDO $pdo)
    {
        $this->db = $pdo;
    }

    public function save(CommentLike $commentLike): void
    {
        $stmt = $this->db->prepare
        ('INSERT INTO commentLikes (commentId, createdAt) VALUES (:commentId, :createdAt)');
        $stmt->execute([
            'commentId' => $commentLike->getCommentId(),
            'createdAt' => $commentLike->getCreatedAt()
        ]);
        $commentLikeId = (int)$this->db->lastInsertId();
        $commentLike->setId($commentLikeId);
    }

    public function countLikesForComment(int $commentId): int
    {
        $stmt = $this->db->prepare('SELECT COUNT(*) FROM commentLikes WHERE commentId = :commentId');
        $stmt->execute(['commentId' => $commentId]);
        return (int)$stmt->fetchColumn();
    }

    public function deleteByCommentId(int $commentId): void
    {
        try {
            $stmt = $this->db->prepare('DELETE FROM commentlikes WHERE commentId = :commentId');
            $stmt->execute(['commentId' => $commentId]);
        } catch (Exception $e) {
            throw new Exception('Failed to delete likes: ' . $e->getMessage());
        }
    }

    public function deleteCommentLikesByArticleId(int $articleId): void
    {
        try {
            $stmt = $this->db->prepare('SELECT id FROM comments WHERE articleId = :articleId');
            $stmt->execute(['articleId' => $articleId]);
            $commentIds = $stmt->fetchAll(PDO::FETCH_COLUMN);

            foreach ($commentIds as $commentId) {
                $this->deleteByCommentId($commentId);
            }
        } catch (Exception $e) {
            throw new Exception('Failed to delete comment likes by articleId: ' . $e->getMessage());
        }
    }
}