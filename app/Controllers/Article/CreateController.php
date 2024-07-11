<?php

namespace ArticleApp\Controllers\Article;

use ArticleApp\Services\Article\CreateService;
use ArticleApp\Services\LogService;
use ArticleApp\RedirectResponse;
use Exception;
use Respect\Validation\Validator as v;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CreateController
{
    private CreateService $createService;
    private LogService $logger;
    private SessionInterface $session;

    public function __construct(CreateService $createService, LogService $logger, SessionInterface $session)
    {
        $this->createService = $createService;
        $this->logger = $logger;
        $this->session = $session;
    }

    public function __invoke(Request $request): RedirectResponse
    {

        $author = $request->request->get('author');
        $title = $request->request->get('title');
        $content = $request->request->get('content');


        $authorValidator = v::stringType()->notEmpty();
        $titleValidator = v::stringType()->notEmpty();
        $contentValidator = v::stringType()->notEmpty();

        try {

            $authorValidator->assert($author);
            $titleValidator->assert($title);
            $contentValidator->assert($content);


            $this->createService->createArticle($author, $title, $content);
            $this->session->getFlashBag()->add('success', 'Article created successfully');
            $this->logger->log('info', 'Article created successfully', [
                'author' => $author,
                'title' => $title,
            ]);


            return new RedirectResponse('/articles');
        } catch (\Respect\Validation\Exceptions\ValidationException $e) {

            $errorMessage = $e->getMessage();
            $this->logger->log('error', 'Validation errors: ' . $errorMessage, [
                'author' => $author,
                'title' => $title,
                'content' => $content,
            ]);

            $this->session->getFlashBag()->add('error', $errorMessage);


            return new RedirectResponse("/article/create?author=" . urlencode($author) .
                "&title=" . urlencode($title) . "&content=" . urlencode($content));
        } catch (Exception $e) {
            $errorMessage = $e->getMessage();
            $this->logger->log('error', 'Error creating article: ' . $errorMessage, [
                'author' => $author,
                'title' => $title,
                'content' => $content,
            ]);

            $this->session->getFlashBag()->add('error', 'Failed to create article: ' . $errorMessage);

            return new RedirectResponse("/article/create?author=" . urlencode($author) .
                "&title=" . urlencode($title) . "&content=" . urlencode($content));
        }
    }
}