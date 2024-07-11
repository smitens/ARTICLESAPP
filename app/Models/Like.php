<?php

namespace ArticleApp\Models;

use Carbon\Carbon;

class Like
{
    private int $likeObjectId;
    private string $likeObjectType;
    private Carbon $createdAt;
    private ?int $id;

    public function __construct
    (
        int $likeObjectId,
        string $likeObjectType,
        Carbon $createdAt,
        ?int $id
    )
    {
        $this->likeObjectId = $likeObjectId;
        $this->likeObjectType = $likeObjectType;
        $this->createdAt = $createdAt;
        $this->id = $id;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLikeObjectId(): int
    {
        return $this->likeObjectId;
    }

    public function getLikeObjectType(): string
    {
        return $this->likeObjectType;
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