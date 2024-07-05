<?php

namespace ArticleApp\Services\Article;

use ArticleApp\Models\Article;
use ArticleApp\Repositories\Article\ArticleRepository;
use ArticleApp\Exceptions\ArticleDisplayException;
use Exception;

class LocalGetByIdService implements GetByIdService
{
    private ArticleRepository $articleRepository;

    public function __construct(ArticleRepository $articleRepository)
    {
        $this->articleRepository = $articleRepository;
    }

    public function getArticleById(int $id): ?Article
    {
        try {
        return $this->articleRepository->findById($id);
        } catch (Exception $e) {
            throw new ArticleDisplayException('Failed to display: ' . $e->getMessage());
        }
    }
}