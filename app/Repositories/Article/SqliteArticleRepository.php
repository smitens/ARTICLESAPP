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
        $stmt = $this->db->query('SELECT * FROM articles ORDER BY createdAt DESC');
        $articles = [];

        while ($data = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $articles[] = $this->createArticleFromData($data);
        }

        return $articles;
    }

    public function save(Article $article): void
    {
        $stmt = $this->db->prepare('INSERT INTO articles 
            (author, title, content, createdAt, updatedAt, deletedAt) 
            VALUES (:author, :title, :content, :createdAt, :updatedAt, :deletedAt)');

        $stmt->execute([
            'author' => $article->getAuthor(),
            'title' => $article->getTitle(),
            'content' => $article->getContent(),
            'createdAt' => $article->getCreatedAt()->toDateTimeString(),
            'updatedAt' => $article->getUpdatedAt() ? $article->getUpdatedAt()->toDateTimeString() : null,
            'deletedAt' => $article->getDeletedAt() ? $article->getDeletedAt()->toDateTimeString() : null,
        ]);

        $articleId = (int)$this->db->lastInsertId();
        $article->setId($articleId);
    }

    public function update(Article $article): void
    {
        $stmt = $this->db->prepare('UPDATE articles 
            SET author = :author, title = :title, content = :content, updatedAt = :updatedAt, deletedAt = :deletedAt 
            WHERE id = :id');

        $stmt->execute([
            'id' => $article->getId(),
            'author' => $article->getAuthor(),
            'title' => $article->getTitle(),
            'content' => $article->getContent(),
            'updatedAt' => $article->getUpdatedAt() ? $article->getUpdatedAt()->toDateTimeString() : null,
            'deletedAt' => $article->getDeletedAt() ? $article->getDeletedAt()->toDateTimeString() : null,
        ]);
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
            isset($data['updatedAt']) ? Carbon::parse($data['updatedAt']) : null,
            isset($data['deletedAt']) ? Carbon::parse($data['deletedAt']) : null,
            $data['id']
        );
    }
}
