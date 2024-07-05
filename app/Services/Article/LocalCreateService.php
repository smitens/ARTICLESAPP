<?php

namespace ArticleApp\Services\Article;

use ArticleApp\Exceptions\CreateArticleException;
use ArticleApp\Models\Article;
use ArticleApp\Repositories\Article\ArticleRepository;
use Carbon\Carbon;
use Exception;

class LocalCreateService implements CreateService
{
    private ArticleRepository $articleRepository;

    public function __construct(ArticleRepository $articleRepository)
    {
        $this->articleRepository = $articleRepository;
    }

    public function createArticle(string $author, string $title, string $content): void
    {
        try {
        $createdAt = Carbon::now();
        $article = new Article($author, $title, $content, $createdAt, null, null, null, 1);
        $this->articleRepository->save($article);
        } catch (Exception $e) {
            throw new CreateArticleException('Failed to create article: ' . $e->getMessage());
        }
    }
}