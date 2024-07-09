<?php

namespace ArticleApp\Services\Article;

use ArticleApp\Models\Article;

interface GetByIdService
{
    public function getArticleWithComments(int $articleId): array;
    public function getArticleById(int $id): ?Article;
}


