<?php

namespace ArticleApp\Services\Article;

interface CreateService
{
    public function createArticle(string $author, string $title, string $content): void;

}

