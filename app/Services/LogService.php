<?php

namespace ArticleApp\Services;

interface LogService
{
    public function log(string $message): void;
}