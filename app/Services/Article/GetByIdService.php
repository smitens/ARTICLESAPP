<?php

namespace ArticleApp\Services\Article;

use ArticleApp\Models\Article;

interface GetByIdService
{
    public function getArticleById(int $id): ?Article;
}


