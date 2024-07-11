<?php

namespace ArticleApp\Repositories\Like;

use PDO;
use ArticleApp\Models\Like;
use Exception;

class SqliteLikeRepository implements LikeRepository
{
    private PDO $db;

    public function __construct(PDO $pdo)
    {
        $this->db = $pdo;
    }

    public function save(Like $like): void
    {
        $stmt = $this->db->prepare
        ('INSERT INTO likes (likeObjectId, likeObjectType, createdAt) 
        VALUES (:likeObjectId, :likeObjectType, :createdAt)');

        try {
            $this->db->beginTransaction();

            $stmt->execute([
                'likeObjectId' => $like->getLikeObjectId(),
                'likeObjectType' => $like->getLikeObjectType(),
                'createdAt' => $like->getCreatedAt()->toDateTimeString(),
            ]);

            $likeId = (int)$this->db->lastInsertId();
            $like->setId($likeId);

            $updateStmt = $this->db->prepare
            ("UPDATE {$like->getLikeObjectType()}s SET likeCount = likeCount + 1 WHERE id = :likeObjectId");
            $updateStmt->execute(['likeObjectId' => $like->getLikeObjectId()]);

            $this->db->commit();
        } catch (Exception $e) {
            $this->db->rollBack();
            throw new Exception('Failed to save like: ' . $e->getMessage());
        }
    }


    public function count(int $likeObjectId, string $likeObjectType): int
    {
        $stmt = $this->db->prepare
        ('SELECT COUNT(*) FROM likes WHERE likeObjectId = :likeObjectId AND likeObjectType = :likeObjectType');
        $stmt->execute([
            'likeObjectId' => $likeObjectId,
            'likeObjectType' => $likeObjectType,
        ]);
        return (int) $stmt->fetchColumn();
    }

    public function delete(int $likeObjectId, string $likeObjectType): void
    {
        try {
            $this->db->beginTransaction();

            $stmt = $this->db->prepare
            ('DELETE FROM likes WHERE likeObjectId = :likeObjectId AND likeObjectType = :likeObjectType');
            $stmt->execute([
                'likeObjectId' => $likeObjectId,
                'likeObjectType' => $likeObjectType,
            ]);

            $updateStmt = $this->db->prepare
            ("UPDATE {$likeObjectType}s SET likeCount = 0 WHERE id = :likeObjectId");
            $updateStmt->execute(['likeObjectId' => $likeObjectId]);

            $this->db->commit();
        } catch (Exception $e) {
            $this->db->rollBack();
            throw new Exception('Failed to delete likes: ' . $e->getMessage());
        }
    }
}