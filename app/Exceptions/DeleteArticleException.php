<?php

namespace ArticleApp\Exceptions;

use Exception;

class DeleteArticleException extends Exception
{
    protected $message = 'Error deleting article.';
}

