<?php

require_once __DIR__ . '/vendor/autoload.php';

use DI\ContainerBuilder;
use DI\Container;
use ArticleApp\Response;
use ArticleApp\RedirectResponse;
use ArticleApp\Services\DatabaseService;
use ArticleApp\Services\SqliteDatabaseService;
use ArticleApp\Repositories\Article\ArticleRepository;
use ArticleApp\Repositories\Article\SqliteArticleRepository;
use ArticleApp\Repositories\Comment\CommentRepository;
use ArticleApp\Repositories\Comment\SqliteCommentRepository;
use ArticleApp\Repositories\Like\Article\ArticleLikeRepository;
use ArticleApp\Repositories\Like\Article\SqliteArticleLikeRepository;
use ArticleApp\Repositories\Like\Comment\CommentLikeRepository;
use ArticleApp\Repositories\Like\Comment\SqliteCommentLikeRepository;
use ArticleApp\Services\LogService;
use ArticleApp\Services\MonologLogService;
use ArticleApp\Services\Article\CreateService;
use ArticleApp\Services\Article\LocalCreateService;
use ArticleApp\Services\Article\UpdateService;
use ArticleApp\Services\Article\LocalUpdateService;
use ArticleApp\Services\Article\DeleteService;
use ArticleApp\Services\Article\LocalDeleteService;
use ArticleApp\Services\Article\GetAllService;
use ArticleApp\Services\Article\LocalGetAllService;
use ArticleApp\Services\Article\GetByIdService;
use ArticleApp\Services\Article\LocalGetByIdService;
use ArticleApp\Services\Comment\CreateCommentService;
use ArticleApp\Services\Comment\LocalCreateCommentService;
use ArticleApp\Services\Comment\DeleteCommentService;
use ArticleApp\Services\Comment\LocalDeleteCommentService;
use ArticleApp\Services\Comment\GetByArticleIdService;
use ArticleApp\Services\Comment\LocalGetByArticleIdService;
use ArticleApp\Services\Like\CountService;
use ArticleApp\Services\Like\LocalCountService;
use ArticleApp\Services\Like\LikeService;
use ArticleApp\Services\Like\LocalLikeService;
use ArticleApp\Controllers\IndexController;
use ArticleApp\Controllers\Article\CreateController;
use ArticleApp\Controllers\Article\DeleteController;
use ArticleApp\Controllers\Article\UpdateController;
use ArticleApp\Controllers\Article\GetAllController;
use ArticleApp\Controllers\Article\GetByIdController;
use ArticleApp\Controllers\Article\CreateFormController;
use ArticleApp\Controllers\Article\UpdateFormController;
use ArticleApp\Controllers\Comment\CreateCommentController;
use ArticleApp\Controllers\Comment\DeleteCommentController;
use ArticleApp\Controllers\Article\ArticleLikeController;
use ArticleApp\Controllers\Comment\CommentLikeController;
use Symfony\Component\HttpFoundation\Request;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use FastRoute\RouteCollector;

$containerBuilder = new ContainerBuilder();

$containerBuilder->addDefinitions([
    DatabaseService::class => function() {
        return new SqliteDatabaseService('storage/database.sqlite');
    },
    PDO::class => function(Container $container) {
        $databaseService = $container->get(DatabaseService::class);
        return $databaseService->connectToDatabase();
    },
    ArticleRepository::class => function (Container $container) {
        return new SqliteArticleRepository($container->get(PDO::class));
    },
    CommentRepository::class => function (Container $container) {
        return new SqliteCommentRepository($container->get(PDO::class));
    },
    ArticleLikeRepository::class => function (Container $container) {
        return new SqliteArticleLikeRepository($container->get(PDO::class));
    },
    CommentLikeRepository::class => function (Container $container) {
        return new SqliteCommentLikeRepository($container->get(PDO::class));
    },
    LogService::class => function () {
        return new MonologLogService();
    },
    CreateService::class => function (Container $container) {
        return new LocalCreateService($container->get(ArticleRepository::class));
    },
    UpdateService::class => function (Container $container) {
        return new LocalUpdateService($container->get(ArticleRepository::class));
    },
    DeleteService::class => function (Container $container) {
        return new LocalDeleteService(
            $container->get(ArticleRepository::class),
            $container->get(ArticleLikeRepository::class),
            $container->get(CommentRepository::class),
            $container->get(CommentLikeRepository::class)
        );
    },
    GetAllService::class => function (Container $container) {
        return new LocalGetAllService($container->get(ArticleRepository::class));
    },
    GetByIdService::class => function (Container $container) {
        return new LocalGetByIdService(
            $container->get(ArticleRepository::class),
            $container->get(CommentRepository::class),
            $container->get(ArticleLikeRepository::class),
            $container->get(CommentLikeRepository::class)
        );
    },
    CreateCommentService::class => function (Container $container) {
        return new LocalCreateCommentService($container->get(CommentRepository::class));
    },
    DeleteCommentService::class => function (Container $container) {
        return new LocalDeleteCommentService(
            $container->get(CommentRepository::class),
            $container->get(CommentLikeRepository::class)
        );
    },
    GetByArticleIdService::class => function (Container $container) {
        return new LocalGetByArticleIdService($container->get(CommentRepository::class));
    },
    CountService::class => function (Container $container) {
        return new LocalCountService(
            $container->get(ArticleLikeRepository::class),
            $container->get(CommentLikeRepository::class)
        );
    },
    LikeService::class => function (Container $container) {
        return new LocalLikeService(
            $container->get(ArticleLikeRepository::class),
            $container->get(CommentLikeRepository::class)
        );
    },
    IndexController::class => function (Container $container) {
        return new IndexController($container->get(LogService::class));
    },
    CreateController::class => function (Container $container) {
        return new CreateController(
            $container->get(CreateService::class),
            $container->get(LogService::class)
        );
    },
    DeleteController::class => function (Container $container) {
        return new DeleteController(
            $container->get(DeleteService::class),
            $container->get(LogService::class)
        );
    },
    UpdateController::class => function (Container $container) {
        return new UpdateController(
            $container->get(UpdateService::class),
            $container->get(LogService::class)
        );
    },
    GetAllController::class => function (Container $container) {
        return new GetAllController(
            $container->get(GetAllService::class),
            $container->get(LogService::class)
        );
    },
    GetByIdController::class => function (Container $container) {
        return new GetByIdController(
            $container->get(GetByIdService::class),
            $container->get(LogService::class)
        );
    },
    CreateFormController::class => function (Container $container) {
        return new CreateFormController($container->get(LogService::class));
    },
    UpdateFormController::class => function (Container $container) {
        return new UpdateFormController(
            $container->get(GetByIdService::class),
            $container->get(LogService::class)
        );
    },
    CreateCommentController::class => function (Container $container) {
        return new CreateCommentController(
            $container->get(CreateCommentService::class),
            $container->get(LogService::class)
        );
    },
    DeleteCommentController::class => function (Container $container) {
        return new DeleteCommentController(
            $container->get(DeleteCommentService::class),
            $container->get(LogService::class)
        );
    },
    ArticleLikeController::class => function (Container $container) {
        return new ArticleLikeController(
            $container->get(LikeService::class),
            $container->get(LogService::class)
        );
    },
    CommentLikeController::class => function (Container $container) {
        return new CommentLikeController(
            $container->get(LikeService::class),
            $container->get(LogService::class)
        );
    },
    'routes' => require __DIR__ . '/routes.php',
]);

$container = $containerBuilder->build();

$loader = new FilesystemLoader(__DIR__ . '/templates');
$twig = new Environment($loader);

$dispatcher = FastRoute\simpleDispatcher(function (RouteCollector $r) use ($container) {
    foreach ($container->get('routes') as $route) {
        $r->addRoute($route[0], $route[1], $route[2]);
    }
});

$httpMethod = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];

if (false !== $pos = strpos($uri, '?')) {
    $uri = substr($uri, 0, $pos);
}
$uri = rawurldecode($uri);

$routeInfo = $dispatcher->dispatch($httpMethod, $uri);

function handleResponse(Environment $twig, $response): void
{
    if ($response instanceof Response) {
        echo $twig->render($response->getTemplate(), $response->getData());
    } elseif ($response instanceof RedirectResponse) {
        header('Location: ' . $response->getLocation());
        exit();
    } else {
        throw new Exception('Unexpected response type.');
    }
}

switch ($routeInfo[0]) {
    case FastRoute\Dispatcher::NOT_FOUND:
        http_response_code(404);
        echo '404 Not Found';
        break;
    case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        $allowedMethods = $routeInfo[1];
        http_response_code(405);
        echo '405 Method Not Allowed';
        break;
    case FastRoute\Dispatcher::FOUND:
        $controllerName = $routeInfo[1];
        $vars = $routeInfo[2];

        $controllerClass = "\\ArticleApp\\Controllers\\" . str_replace('/', '\\', $controllerName);
        $controllerInstance = $container->get($controllerClass);

        $request = Request::createFromGlobals();

        $result = $controllerInstance($request, $vars);

        handleResponse($twig, $result);
        break;
}