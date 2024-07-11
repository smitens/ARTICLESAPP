<?php

namespace ArticleApp\Controllers\Article;

use ArticleApp\Response;
use ArticleApp\Services\LogService;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CreateFormController
{
    private LogService $logger;
    private SessionInterface $session;

    public function __construct(LogService $logger, SessionInterface $session)
    {
        $this->logger = $logger;
        $this->session = $session;
    }

    public function __invoke(): Response
    {
        $this->logger->log('info', 'Create form showed successfully.');

        $flashMessages = $this->session->getFlashBag()->all();

        return new Response('createform.twig', ['flashMessages' => $flashMessages]);
    }
}