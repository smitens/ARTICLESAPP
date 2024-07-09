<?php

namespace ArticleApp\Exceptions;

use Exception;

class CreateCommentException extends Exception
{
    protected $message = 'Error creating comment.';
}

