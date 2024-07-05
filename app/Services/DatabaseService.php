<?php

namespace ArticleApp\Services;

use PDO;

interface DatabaseService
{
    public function initializeDatabase(): void;
    public function connectToDatabase(): ?PDO;
    public function createArticlesTable(PDO $pdo): void;
}
