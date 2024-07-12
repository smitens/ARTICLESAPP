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
        $oldInput = $flashMessages['old_input'][0] ?? ['author' => '', 'title' => '', 'content' => ''];

        return new Response('createform.twig', [
            'flashMessages' => $flashMessages,
            'oldInput' => $oldInput,
        ]);
    }
}