<?php

namespace ArticleApp\Services\Comment;

interface CreateCommentService
{
    public function create(int $articleId, string $author, string $content): void;
}

