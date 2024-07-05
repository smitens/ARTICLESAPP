<?php

namespace ArticleApp\Services\Article;

interface UpdateService
{
    public function updateArticle(int $id, string $author, string $title, string $content): void;
}

