<?php

namespace ArticleApp\Services\Comment;

interface GetByArticleIdService
{
    public function getCommentByArticleId(int $articleId): array;
}


