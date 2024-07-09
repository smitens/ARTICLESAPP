<?php

namespace ArticleApp\Repositories\Like\Article;

use ArticleApp\Models\ArticleLike;

interface ArticleLikeRepository
{
    public function save(ArticleLike $articleLike): void;
    public function countLikesForArticle(int $articleId): int;
    public function deleteByArticleId(int $articleId): void;
}
