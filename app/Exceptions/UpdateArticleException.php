<?php

namespace ArticleApp\Exceptions;

use Exception;

class UpdateArticleException extends Exception
{
    protected $message = 'Error updating article.';
}

