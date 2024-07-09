<?php

namespace ArticleApp\Repositories\Article;

use ArticleApp\Models\Article;

interface ArticleRepository
{
    public function findById($id): ?Article;
    public function findAll(): array;
    public function save(Article $article): void;
    public function update(Article $article): void;
    public function deleteSoft($id): void;
    public function delete($id): void;
}
