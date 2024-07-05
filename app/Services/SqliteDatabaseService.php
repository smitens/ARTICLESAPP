<?php

namespace ArticleApp\Services;

use PDO;
use PDOException;
use Exception;

class SqliteDatabaseService implements DatabaseService
{
    private string $databaseFile;

    public function __construct(string $databaseFile = 'storage/database.sqlite')
    {
        $this->databaseFile = $databaseFile;
        $this->initializeDatabase();
    }

    public function initializeDatabase(): void
    {
        $pdo = $this->connectToDatabase();

        if ($pdo) {

            $this->createArticlesTable($pdo);
        } else {
            throw new Exception("Failed to connect to the database.");
        }
    }

    public function connectToDatabase(): ?PDO
    {
        try {
            $pdo = new PDO('sqlite:' . $this->databaseFile);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $pdo;
        } catch (PDOException $e) {
            throw new Exception("Database connection error: " . $e->getMessage());
        }
    }

    public function createArticlesTable(PDO $pdo): void
    {
        try {
            $pdo->exec('CREATE TABLE IF NOT EXISTS articles (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                author TEXT NOT NULL,
                title TEXT NOT NULL,
                content TEXT NOT NULL,
                createdAt DATETIME DEFAULT CURRENT_TIMESTAMP,
                updatedAt DATETIME,
                deletedAt DATETIME
            )');
        } catch (PDOException $e) {
            throw new Exception("Error creating articles table: " . $e->getMessage());
        }
    }
}