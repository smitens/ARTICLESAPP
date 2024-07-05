<?php

namespace ArticleApp\Services\Article;

use ArticleApp\Repositories\Article\ArticleRepository;
use ArticleApp\Exceptions\ArticleDisplayException;
use Exception;

class LocalGetAllService implements GetAllService
{
    private ArticleRepository $articleRepository;

    public function __construct(ArticleRepository $articleRepository)
    {
        $this->articleRepository = $articleRepository;
    }

    public function getAllArticles(): array
    {
        try {
        return $this->articleRepository->findAll();
        } catch (Exception $e) {
            throw new ArticleDisplayException('Failed to display: ' . $e->getMessage());
        }
    }
}