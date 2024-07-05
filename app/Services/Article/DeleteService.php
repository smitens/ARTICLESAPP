<?php

namespace ArticleApp\Services\Article;

interface DeleteService
{
    public function deleteArticle(int $id): void;
}

