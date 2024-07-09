<?php

namespace ArticleApp\Repositories\Comment;

use ArticleApp\Models\Comment;

interface CommentRepository
{
    public function save(Comment $comment): void;
    public function getByArticleId(int $articleId): array;
    public function delete(int $id): void;
    public function deleteByArticleId(int $articleId): void;
}