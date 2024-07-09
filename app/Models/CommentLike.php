<?php

namespace ArticleApp\Models;

use Carbon\Carbon;

class CommentLike
{
    private int $commentId;
    private Carbon $createdAt;
    private ?int $id;

    public function __construct
    (
        int $commentId,
        Carbon $createdAt,
        ?int $id
    )
    {
        $this->commentId = $commentId;
        $this->createdAt = $createdAt;
        $this->id = $id;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCommentId(): int
    {
        return $this->commentId;
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