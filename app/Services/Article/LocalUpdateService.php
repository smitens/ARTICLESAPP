<?php

namespace ArticleApp\Services\Article;

use ArticleApp\Repositories\Article\ArticleRepository;
use ArticleApp\Exceptions\UpdateArticleException;
use Exception;
use Carbon\Carbon;

class LocalUpdateService implements UpdateService
{
    private ArticleRepository $articleRepository;

    public function __construct(ArticleRepository $articleRepository)
    {
        $this->articleRepository = $articleRepository;
    }

    public function updateArticle(int $id, string $author, string $title, string $content): void
    {
        try{
        $article = $this->articleRepository->findById($id);
        if ($article) {
            $article->setAuthor($author);
            $article->setTitle($title);
            $article->setContent($content);
            $article->setUpdatedAt(Carbon::now());
            $this->articleRepository->update($article);
        }
        } catch (Exception $e) {
            throw new UpdateArticleException('Failed to update article: ' . $e->getMessage());
        }
    }
}