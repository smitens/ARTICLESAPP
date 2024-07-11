<?php

namespace ArticleApp\Repositories\Article;

use PDO;
use ArticleApp\Models\Article;
use Carbon\Carbon;

class SqliteArticleRepository implements ArticleRepository
{
    private PDO $db;

    public function __construct(PDO $pdo)
    {
        $this->db = $pdo;
    }

    public function findById($id): ?Article
    {
        $stmt = $this->db->prepare('SELECT * FROM articles WHERE id = :id');
        $stmt->execute(['id' => $id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$data) {
            return null;
        }

        return $this->createArticleFromData($data);
    }

    public function findAll(): array
    {
        $stmt = $this->db->query('SELECT * FROM articles WHERE status = 1 ORDER BY createdAt DESC');
        $articles = [];

        while ($data = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $articles[] = $this->createArticleFromData($data);
        }

        return $articles;
    }

    public function save(Article $article): void
    {
        $stmt = $this->db->prepare('INSERT INTO articles 
            (author, title, content, createdAt, updatedAt, likeCount, commentCount, deletedAt, status) 
            VALUES (:author, :title, :content, :createdAt, :likeCount, :commentCount, :updatedAt, :deletedAt, :status)');

        $stmt->execute([
            'author' => $article->getAuthor(),
            'title' => $article->getTitle(),
            'content' => $article->getContent(),
            'createdAt' => $article->getCreatedAt()->toDateTimeString(),
            'likeCount' => $article->getLikeCount(),
            'commentCount' => $article->getCommentCount(),
            'updatedAt' => $article->getUpdatedAt() ? $article->getUpdatedAt()->toDateTimeString() : null,
            'deletedAt' => $article->getDeletedAt() ? $article->getDeletedAt()->toDateTimeString() : null,
            'status' => 1,
        ]);

        $articleId = (int)$this->db->lastInsertId();
        $article->setId($articleId);
    }

    public function update(Article $article): void
    {
        $stmt = $this->db->prepare('UPDATE articles 
            SET status = :status, author = :author, title = :title, content = :content, likeCount = :likeCount, 
                commentCount = :commentCount, updatedAt = :updatedAt, deletedAt = :deletedAt 
            WHERE id = :id');

        $stmt->execute([
            'id' => $article->getId(),
            'author' => $article->getAuthor(),
            'title' => $article->getTitle(),
            'content' => $article->getContent(),
            'likeCount' => $article->getLikeCount(),
            'commentCount' => $article->getCommentCount(),
            'updatedAt' => $article->getUpdatedAt() ? $article->getUpdatedAt()->toDateTimeString() : null,
            'deletedAt' => $article->getDeletedAt() ? $article->getDeletedAt()->toDateTimeString() : null,
            'status' => $article->getStatus()
        ]);
    }

    public function deleteSoft($id): void
    {
        try {
            $deletedAt = Carbon::now()->toDateTimeString();

            $stmt = $this->db->prepare
            ('UPDATE articles SET status = :status, deletedAt = :deletedAt WHERE id = :id');

            $stmt->execute([
                'status' => 0,
                'deletedAt' => $deletedAt,
                'id' => $id
            ]);

            echo "Article updated successfully.";
        } catch (\PDOException $e) {
            throw new \Exception("Failed to delete article: " . $e->getMessage());
        }
    }

    public function delete($id): void
    {
        $stmt = $this->db->prepare('DELETE FROM articles WHERE id = :id');
        $stmt->execute(['id' => $id]);
    }

    private function createArticleFromData(array $data): Article
    {
        return new Article(
            $data['author'],
            $data['title'],
            $data['content'],
            Carbon::parse($data['createdAt']),
            0,
            0,
            isset($data['updatedAt']) ? Carbon::parse($data['updatedAt']) : null,
            isset($data['deletedAt']) ? Carbon::parse($data['deletedAt']) : null,
            $data['id'],
            $data['status'],
        );
    }
}