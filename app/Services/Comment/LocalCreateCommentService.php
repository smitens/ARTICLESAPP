<?php

namespace ArticleApp\Services\Comment;

use ArticleApp\Exceptions\CreateCommentException;
use ArticleApp\Models\Comment;
use ArticleApp\Repositories\Comment\CommentRepository;
use Carbon\Carbon;
use Exception;

class LocalCreateCommentService implements CreateCommentService
{
    private CommentRepository $commentRepository;

    public function __construct(CommentRepository $commentRepository)
    {
        $this->commentRepository = $commentRepository;
    }

    public function create(int $articleId, string $author, string $content): void
    {
        try {
            $createdAt = Carbon::now();
            $comment = new Comment($articleId, $author, $content, $createdAt, 0, null, null);
            $this->commentRepository->save($comment);

        } catch (Exception $e) {
            throw new CreateCommentException('Failed to create comment: ' . $e->getMessage());
        }
    }
}
