<?php

namespace ArticleApp\Services\Comment;

interface DeleteCommentService
{
    public function delete(int $id): void;
    public function deleteByArticleId(int $articleId): void;
}

