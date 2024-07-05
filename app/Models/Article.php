<?php

namespace ArticleApp\Models;

use Carbon\Carbon;

class Article
{
    private string $author;
    private string $title;
    private string $content;
    private Carbon $createdAt;
    private ?Carbon $updatedAt;
    private ?Carbon $deletedAt;
    private ?int $id;
    private int $status;


    public function __construct
    (
        string $author,
        string $title,
        string $content,
        Carbon $createdAt,
        ?Carbon $updatedAt = null,
        ?Carbon $deletedAt = null,
        ?int $id = null,
        int $status = 1

    )
    {
        $this->author = $author;
        $this->title = $title;
        $this->content = $content;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
        $this->deletedAt = $deletedAt;
        $this->id = $id;
        $this->status = $status;
    }

    public function getStatus(): int
    {
        return $this->status;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAuthor(): string
    {
        return $this->author;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function getCreatedAt(): Carbon
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): ?Carbon
    {
        return $this->updatedAt;
    }

    public function getDeletedAt(): ?Carbon
    {
        return $this->deletedAt;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function setStatus(int $status): void
    {
        $this->status = $status;
    }

    public function setAuthor($author)
    {
        $this->author = $author;
    }

    public function setTitle($title): void
    {
        $this->title = $title;
    }

    public function setContent($content): void
    {
        $this->content = $content;
    }

    public function setUpdatedAt(?Carbon $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

    public function setDeletedAt(?Carbon $deletedAt): void
    {
        $this->deletedAt = $deletedAt;
    }
}