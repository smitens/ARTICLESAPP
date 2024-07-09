<?php

namespace ArticleApp\Exceptions;

use Exception;

class DeleteCommentException extends Exception
{
    protected $message = 'Error deleting comment.';
}

