<?php

namespace ArticleApp\Repositories\Like\Comment;

use ArticleApp\Models\CommentLike;

interface CommentLikeRepository
{
    public function save(CommentLike $commentLike): void;
    public function countLikesForComment(int $commentId): int;
    public function deleteByCommentId(int $commentId): void;
    public function deleteCommentLikesByArticleId(int $articleId): void;
}
