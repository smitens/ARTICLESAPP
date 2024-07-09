<?php

namespace ArticleApp\Services\Comment;

interface DeleteCommentService
{
    public function deleteComment(int $id): void;
    public function deleteByArticleId(int $articleId): void;
}

