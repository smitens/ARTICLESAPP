<?php

namespace ArticleApp\Repositories\Comment;

use ArticleApp\Models\Comment;
use Carbon\Carbon;
use PDO;
use Exception;

class SqliteCommentRepository implements CommentRepository
{
    private PDO $db;

    public function __construct(PDO $pdo)
    {
        $this->db = $pdo;
    }

    public function save(Comment $comment): void
    {
        $stmt = $this->db->prepare('INSERT INTO comments 
            (articleId, author, content, createdAt) 
            VALUES (:articleId, :author, :content, :createdAt)');

        try {

            $stmt->execute([
                'articleId' => $comment->getArticleId(),
                'author' => $comment->getAuthor(),
                'content' => $comment->getContent(),
                'createdAt' => $comment->getCreatedAt()->toDateTimeString(),
            ]);

            $commentId = (int)$this->db->lastInsertId();
            $comment->setId($commentId);

        } catch (Exception $e) {
            $this->db->rollBack();
            throw new Exception('Failed to save comment: ' . $e->getMessage());
        }
    }

    public function getById(int $id): ?Comment
    {
        $stmt = $this->db->prepare('SELECT * FROM comments WHERE id = :id');
        $stmt->execute(['id' => $id]);

        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$data) {
            return null;
        }

        return $this->createCommentFromData($data);
    }

    public function getByArticleId(int $articleId): array
    {
        $stmt = $this->db->prepare('SELECT * FROM comments WHERE articleId = :articleId ORDER BY createdAt DESC');
        $comments = [];
        $stmt->execute(['articleId' => $articleId]);

        while ($data = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $comments[] = $this->createCommentFromData($data);
        }

        return $comments;
    }

    public function delete(int $id): void
    {
        $query = "DELETE FROM comments WHERE id = :id";

        try {
            $stmt = $this->db->prepare($query);
            $stmt->execute(['id' => $id]);

            if ($stmt->rowCount() > 0) {
                $this->updateArticleCommentCount($id);
            } else {
                throw new Exception('Comment not deleted: ID not found.');
            }
        } catch (Exception $e) {
            throw new Exception('Failed to delete comment: ' . $e->getMessage());
        }
    }

    public function deleteByArticleId(int $articleId): void
    {
        try {
            $stmt = $this->db->prepare('DELETE FROM comments WHERE articleId = :articleId');
            $stmt->execute(['articleId' => $articleId]);
        } catch (Exception $e) {
            throw new Exception('Failed to delete comments: ' . $e->getMessage());
        }
    }

    private function createCommentFromData(array $data): Comment
    {
        return new Comment(
            $data['articleId'],
            $data['author'],
            $data['content'],
            Carbon::parse($data['createdAt']),
            $data['likeCount'],
            isset($data['deletedAt']) ? Carbon::parse($data['deletedAt']) : null,
            $data['id'],
        );
    }

    public function countComments(int $articleId): int
    {
        $stmt = $this->db->prepare('SELECT COUNT(*) FROM comments WHERE articleId = :articleId');
        $stmt->execute(['articleId' => $articleId]);
        return (int)$stmt->fetchColumn();
    }

    private function updateArticleCommentCount(int $articleId): void
    {
        $count = $this->countComments($articleId);

        $updateStmt = $this->db->prepare('UPDATE articles SET commentCount = :commentCount WHERE id = :articleId');
        $updateStmt->execute([
            'commentCount' => $count,
            'articleId' => $articleId,
        ]);
    }
}