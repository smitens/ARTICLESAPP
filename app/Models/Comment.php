<?php

namespace ArticleApp\Models;

use Carbon\Carbon;

class Comment
{
    private int $articleId;
    private string $author;
    private string $content;
    private int $likeCount;
    private Carbon $createdAt;
    private ?Carbon $deletedAt;
    private ?int $id;


    public function __construct
    (
        int $articleId,
        string $author,
        string $content,
        Carbon $createdAt,
        int $likeCount = 0,
        ?Carbon $deletedAt = null,
        ?int $id = null

    )
    {
        $this->articleId = $articleId;
        $this->author = $author;
        $this->content = $content;
        $this->likeCount = $likeCount;
        $this->createdAt = $createdAt;
        $this->deletedAt = $deletedAt;
        $this->id = $id;
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getArticleId(): int
    {
        return $this->articleId;
    }

    public function getAuthor(): string
    {
        return $this->author;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function getCreatedAt(): Carbon
    {
        return $this->createdAt;
    }

    public function getDeletedAt(): ?Carbon
    {
        return $this->deletedAt;
    }

    public function getLikeCount(): int
    {
        return $this->likeCount;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function setArticleId(int $articleId): void
    {
        $this->articleId = $articleId;
    }

    public function setAuthor(string $author): void
    {
        $this->author = $author;
    }

    public function setContent(string $content): void
    {
        $this->content = $content;
    }

    public function setCreatedAt(Carbon $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public function setDeletedAt(?Carbon $deletedAt): void
    {
        $this->deletedAt = $deletedAt;
    }

    public function setLikeCount(int $likeCount): void
    {
        $this->likeCount = $likeCount;
    }
}