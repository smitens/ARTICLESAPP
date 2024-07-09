<?php

namespace ArticleApp\Services\Like;

interface LikeService
{
    public function likeArticle(int $articleId): void;
    public function likeComment(int $articleId): void;
}
