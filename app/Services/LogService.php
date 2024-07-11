<?php

namespace ArticleApp\Services;

interface LogService
{
    public function log(string $level, string $message, array $context = []): void;
}