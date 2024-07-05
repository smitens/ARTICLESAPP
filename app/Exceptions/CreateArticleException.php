<?php

namespace ArticleApp\Exceptions;

use Exception;

class CreateArticleException extends Exception
{
    protected $message = 'Error creating article.';
}

