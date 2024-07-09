<?php

namespace ArticleApp\Services\Comment;

interface CreateCommentService
{
    public function createComment(int $articleId, string $author, string $content): void;
}

