<?php

namespace ArticleApp\Services\Article;

use ArticleApp\Repositories\Article\ArticleRepository;
use ArticleApp\Repositories\Like\Article\SqliteArticleLikeRepository;
use ArticleApp\Repositories\Like\Comment\SqliteCommentLikeRepository;
use ArticleApp\Repositories\Comment\SqliteCommentRepository;
use ArticleApp\Exceptions\DeleteArticleException;
use Exception;

class LocalDeleteService implements DeleteService
{
    private ArticleRepository $articleRepository;
    private SqliteArticleLikeRepository $sqliteArticleLikeRepository;
    private SqliteCommentRepository $sqliteCommentRepository;
    private SqliteCommentLikeRepository $sqliteCommentLikeRepository;

    public function __construct
    (
        ArticleRepository $articleRepository,
        SqliteArticleLikeRepository $sqliteArticleLikeRepository,
        SqliteCommentRepository $sqliteCommentRepository,
        SqliteCommentLikeRepository $sqliteCommentLikeRepository
    )
    {
        $this->articleRepository = $articleRepository;
        $this->sqliteArticleLikeRepository = $sqliteArticleLikeRepository;
        $this->sqliteCommentRepository = $sqliteCommentRepository;
        $this->sqliteCommentLikeRepository = $sqliteCommentLikeRepository;
    }

    public function deleteArticle(int $id): void
    {
        try{
            $this->sqliteArticleLikeRepository->deleteByArticleId($id);
            $this->sqliteCommentLikeRepository->deleteCommentLikesByArticleId($id);
            $this->sqliteCommentRepository->deleteByArticleId($id);

            $this->articleRepository->delete($id);
        } catch (Exception $e) {
            throw new DeleteArticleException('Failed to delete article: ' . $e->getMessage());
        }
    }
}