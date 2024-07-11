<?php

namespace ArticleApp\Controllers\Article;

use ArticleApp\Response;
use ArticleApp\Services\Article\GetByIdService;
use ArticleApp\Services\LogService;
use Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class GetByIdController
{
    private GetByIdService $getByIdService;
    private LogService $logger;
    private SessionInterface $session;

    public function __construct
    (
        GetByIdService $getByIdService,
        LogService $logger,
        SessionInterface $session
    )
    {
        $this->getByIdService = $getByIdService;
        $this->logger = $logger;
        $this->session = $session;
    }

    public function __invoke(Request $request, array $vars): Response
    {
        $id = (int) $vars['id'];

        $flashMessages = $this->session->getFlashBag()->all();

        try {
            $articleWithCommentsAndLikes = $this->getByIdService->getArticleWithComments($id);

            if (!$articleWithCommentsAndLikes['article']) {
                $this->logger->log('error', 'Article ' . $id . ' not found');
                return new Response('Article not found', []);
            }

            $article = $articleWithCommentsAndLikes['article'];
            $comments = $articleWithCommentsAndLikes['comments'];
            $articleLikes = $articleWithCommentsAndLikes['articleLikes'];
            $commentLikes = $articleWithCommentsAndLikes['commentLikes'];


            $this->logger->log('info', 'Article Id no. ' . $id . ' displayed successfully.');
            return new Response('article.twig', [
                'article' => $article,
                'comments' => $comments,
                'articleLikes' => $articleLikes,
                'commentLikes' => $commentLikes,
                'flashMessages' => $flashMessages
            ]);
        } catch (Exception $e) {
            $this->logger->log('error', 'Error displaying article Id no. ' . $id . ' : ' . $e->getMessage());
            return new Response('article.twig', ['flashMessages' => $flashMessages]);
        }
    }
}