<?php

namespace ArticleApp\Repositories\Like;

use ArticleApp\Models\Like;

interface LikeRepository
{
    public function save(Like $like): void;
    public function count(int $likeObjectId, string $likeObjectType): int;
    public function delete(int $likeObjectId, string $likeObjectType): void;
}

