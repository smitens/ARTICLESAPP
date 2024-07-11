<?php
namespace ArticleApp\Controllers\Article;

use ArticleApp\Response;
use ArticleApp\Services\Article\GetByIdService;
use ArticleApp\Services\LogService;
use Exception;
use Symfony\Component\HttpFoundation\Request;

class GetByIdController
{
    private GetByIdService $getByIdService;
    private LogService $logger;

    public function __construct(GetByIdService $getByIdService, LogService $logger)
    {
        $this->getByIdService = $getByIdService;
        $this->logger = $logger;
    }

    public function __invoke(Request $request, array $vars): Response
    {
        $id = (int) $vars['id'];

        try {
            $articleWithCommentsAndLikes = $this->getByIdService->getArticleWithComments($id);

            if (!$articleWithCommentsAndLikes['article']) {
                $this->logger->log('error','Article ' . $id . ' not found');
                return new Response('Article not found', []);
            }

            $article = $articleWithCommentsAndLikes['article'];
            $comments = $articleWithCommentsAndLikes['comments'];
            $articleLikes = $articleWithCommentsAndLikes['articleLikes'];
            $commentLikes = $articleWithCommentsAndLikes['commentLikes'];

            $this->logger->log('info','Article Id no. ' . $id . ' displayed successfully.');
            return new Response('article.twig', [
                'article' => $article,
                'comments' => $comments,
                'articleLikes' => $articleLikes,
                'commentLikes' => $commentLikes
            ]);
        } catch (Exception $e) {
            $this->logger->log('error','Error displaying article Id no. ' . $id . ' : ' . $e->getMessage());
            return new Response('error.twig', ['error_message' => $e->getMessage()]);
        }
    }
}