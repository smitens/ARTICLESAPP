<?php

namespace ArticleApp\Models;

use Carbon\Carbon;

class ArticleLike
{
    private int $articleId;
    private Carbon $createdAt;
    private ?int $id;

    public function __construct
    (
        int $articleId,
        Carbon $createdAt,
        ?int $id
    )
    {
        $this->articleId = $articleId;
        $this->createdAt = $createdAt;
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

    public function getCreatedAt(): Carbon
    {
        return $this->createdAt;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }
}