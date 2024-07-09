<?php

namespace ArticleApp\Repositories\Like\Article;

use PDO;
use ArticleApp\Models\ArticleLike;
use Exception;

class SqliteArticleLikeRepository implements ArticleLikeRepository
{
    private PDO $db;

    public function __construct(PDO $pdo)
    {
        $this->db = $pdo;
    }

    public function save(ArticleLike $articleLike): void
    {
        $stmt = $this->db->prepare
        ('INSERT INTO articleLikes (articleId, createdAt) VALUES (:articleId, :createdAt)');
        $stmt->execute([
            'articleId' => $articleLike->getArticleId(),
            'createdAt' => $articleLike->getCreatedAt()
        ]);

        $articleLikeId = (int)$this->db->lastInsertId();
        $articleLike->setId($articleLikeId);
    }

    public function countLikesForArticle(int $articleId): int
    {
        $stmt = $this->db->prepare('SELECT COUNT(*) FROM articleLikes WHERE articleId = :articleId');
        $stmt->execute(['articleId' => $articleId]);
        return (int)$stmt->fetchColumn();
    }

    public function deleteByArticleId(int $articleId): void
    {
        try {
            $stmt = $this->db->prepare('DELETE FROM articlelikes WHERE articleId = :articleId');
            $stmt->execute(['articleId' => $articleId]);
        } catch (Exception $e) {
            throw new Exception('Failed to delete likes: ' . $e->getMessage());
        }
    }
}