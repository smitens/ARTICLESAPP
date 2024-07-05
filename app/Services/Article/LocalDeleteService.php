<?php

namespace ArticleApp\Services\Article;

use ArticleApp\Repositories\Article\ArticleRepository;
use ArticleApp\Exceptions\DeleteArticleException;
use Exception;

class LocalDeleteService implements DeleteService
{
    private ArticleRepository $articleRepository;

    public function __construct(ArticleRepository $articleRepository)
    {
        $this->articleRepository = $articleRepository;
    }

    public function deleteArticle(int $id): void
    {
        try{
        $this->articleRepository->delete($id);
        } catch (Exception $e) {
            throw new DeleteArticleException('Failed to delete article: ' . $e->getMessage());
        }
    }
}