# ArticleApp

ArticleApp is a web-based PHP application that allows you to manage and display articles. The application provides functionality to create, read, update, and delete articles. It uses TailwindCSS for visual formatting, Monolog for logging, and PHP-DI for dependency injection.

## Features

- Show list of articles
- Display single article
- Create new article
- Update article
- Delete article

## Installation

1. Clone the repository:

    ```sh
    git clone https://github.com/yourusername/ArticleApp.git
    ```

2. Install the required dependencies using Composer:

    ```sh
    composer install
    ```
   ```sh
    data storage directory with database.sqlite file in it will be created after you run the application 
    (existing storage directory with test data file added in repository as an example)
    ```

## Usage

1. Start the local development server:

    ```sh
    php -S localhost:8000 
    ```

2. Open your browser and navigate to:

    ```
    http://localhost:8000
    ```

## Logging

The application uses [Monolog](https://github.com/Seldaek/monolog) for logging (articleapp.log.example file with test data added in repository as an example).

## Dependency Injection

The application uses [PHP-DI](https://php-di.org/) for dependency injection.

---

This project was a part of Codelex programmin in PHP course homework assignments.
By following this README, you should be able to set up and run the ArticleApp application on your local machine. 
