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

            $this->createTables($pdo);
        } else {
            throw new Exception("Failed to connect to the database.");
        }
    }

    public function connectToDatabase(): ?PDO
    {
        try {
            $pdo = new PDO('sqlite:' . $this->databaseFile);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->exec('PRAGMA foreign_keys = ON;');
            return $pdo;
        } catch (PDOException $e) {
            throw new Exception("Database connection error: " . $e->getMessage());
        }
    }

    public function createTables(PDO $pdo): void
    {
        $this->createArticlesTable($pdo);
        $this->createCommentsTable($pdo);
        $this->createLikesTable($pdo);
    }

    private function createArticlesTable(PDO $pdo): void
    {
        try {
            $pdo->exec('CREATE TABLE IF NOT EXISTS articles (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                author TEXT NOT NULL,
                title TEXT NOT NULL,
                content TEXT NOT NULL,
                createdAt DATETIME DEFAULT CURRENT_TIMESTAMP,
                updatedAt DATETIME,
                deletedAt DATETIME,
                status INTEGER NOT NULL DEFAULT 1,
                likeCount INTEGER NOT NULL DEFAULT 0,
                commentCount INTEGER NOT NULL DEFAULT 0                    
            )');
        } catch (PDOException $e) {
            throw new Exception("Error creating articles table: " . $e->getMessage());
        }
    }

        private function createCommentsTable(PDO $pdo): void
        {
            try {
                $pdo->exec('CREATE TABLE IF NOT EXISTS comments 
            (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                articleId INTEGER,
                author TEXT,
                content TEXT,
                createdAt DATETIME DEFAULT CURRENT_TIMESTAMP,
                likeCount INTEGER NOT NULL DEFAULT 0,
                FOREIGN KEY (articleId) REFERENCES articles(id) ON DELETE CASCADE
            );');
            } catch (PDOException $e) {
                throw new Exception("Error creating comments table: " . $e->getMessage());
            }
        }

        private function createLikesTable(PDO $pdo): void
        {
            try {
                $pdo->exec('CREATE TABLE IF NOT EXISTS likes 
            (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                likeObjectId INTEGER NOT NULL,
                likeObjectType STRING NOT NULL,
                createdAt DATETIME DEFAULT CURRENT_TIMESTAMP
            );');
            } catch (PDOException $e) {
                throw new Exception("Error creating article likes table: " . $e->getMessage());
            }
        }
}