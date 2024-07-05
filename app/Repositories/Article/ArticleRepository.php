<?php

namespace ArticleApp\Repositories\Article;

use ArticleApp\Models\Article;

interface ArticleRepository
{
    public function findById($id);
    public function findAll();
    public function save(Article $article);
    public function update(Article $article);
    public function delete($id);
}
