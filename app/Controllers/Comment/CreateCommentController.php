<?php

namespace ArticleApp\Controllers\Comment;

use ArticleApp\Services\Comment\CreateCommentService;
use ArticleApp\Services\LogService;
use ArticleApp\RedirectResponse;
use Respect\Validation\Exceptions\ValidationException;
use Respect\Validation\Validator as v;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CreateCommentController
{
    private CreateCommentService $createCommentService;
    private LogService $logger;
    private SessionInterface $session;

    public function __construct(
        CreateCommentService $createCommentService,
        LogService $logger,
        SessionInterface $session
    ) {
        $this->createCommentService = $createCommentService;
        $this->logger = $logger;
        $this->session = $session;
    }

    public function __invoke(Request $request, array $vars): RedirectResponse
    {
        $articleId = (int) $vars['id'];
        $author = $request->request->get('author', '');
        $content = $request->request->get('content', '');

        $authorValidator = v::stringType()->notEmpty()->setName('Author');
        $contentValidator = v::stringType()->notEmpty()->setName('Content');

        try {
            $authorValidator->assert($author);
            $contentValidator->assert($content);

            $this->createCommentService->create($articleId, $author, $content);

            $this->session->getFlashBag()->add('success', 'Comment created successfully');
            $this->logger->log('info', 'Comment created successfully', [
                'author' => $author,
                'content' => $content,
            ]);

            return new RedirectResponse("/article/{$articleId}");
        } catch (ValidationException $e) {
            $messages = $e->getMessages();
            $customMessages = [
                'Author' => 'Author field is required and cannot be empty.',
                'Content' => 'Comment field is required and cannot be empty.',
            ];

            $errorMessage = [];
            foreach ($messages as $field => $msg) {
                if (isset($customMessages[$field])) {
                    $errorMessage[] = $customMessages[$field];
                } else {
                    $errorMessage[] = $msg;
                }
            }
            $errorMessage = implode(' ', $errorMessage);

            $this->session->getFlashBag()->add('error', $errorMessage);
            $this->session->getFlashBag()->add('old_comment_input', [
                'author' => $author,
                'content' => $content,
            ]);
            $this->logger->log('error', 'Validation errors: ' . $errorMessage, [
                'author' => $author,
                'content' => $content,
            ]);

            return new RedirectResponse("/article/{$articleId}");
        } catch (\Exception $e) {
            $errorMessage = 'Failed to create comment: ' . $e->getMessage();
            $this->session->getFlashBag()->add('error', $errorMessage);
            $this->session->getFlashBag()->add('old_comment_input', [
                'author' => $author,
                'content' => $content,
            ]);
            $this->logger->log('error', 'Error creating comment: ' . $errorMessage, [
                'author' => $author,
                'content' => $content,
            ]);

            return new RedirectResponse("/article/{$articleId}");
        }
    }
}