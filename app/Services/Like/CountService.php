<?php

namespace ArticleApp\Services\Like;

interface CountService
{
    public function getArticleLikesCount(int $articleId): int;
    public function getCommentLikesCount(int $commentId): int;
}

