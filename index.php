<?php

require_once __DIR__ . '/vendor/autoload.php';

use ArticleApp\Controllers\CreateController;
use ArticleApp\Controllers\CreateFormController;
use ArticleApp\Controllers\DeleteController;
use ArticleApp\Controllers\UpdateFormController;
use ArticleApp\Controllers\UpdateController;
use ArticleApp\Controllers\GetAllController;
use ArticleApp\Controllers\GetByIdController;
use ArticleApp\Response;
use ArticleApp\RedirectResponse;
use ArticleApp\Repositories\Article\SqliteArticleRepository;
use ArticleApp\Repositories\Article\ArticleRepository;
use ArticleApp\Services\DatabaseService;
use ArticleApp\Services\SqliteDatabaseService;
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
use DI\Container;
use FastRoute\RouteCollector;
use Symfony\Component\HttpFoundation\Request;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

$container = new Container();


$container->set(DatabaseService::class, function() {
    return new SqliteDatabaseService('storage/database.sqlite');
});

$container->set(ArticleRepository::class, function (Container $container) {
    $databaseService = $container->get(DatabaseService::class);
    $pdo = $databaseService->connectToDatabase();
    return new SqliteArticleRepository($pdo);
});

$container->set(LogService::class, function () {
    return new MonologLogService();
});

$container->set(CreateService::class, function (Container $container) {
    return new LocalCreateService(
        $container->get(ArticleRepository::class)
    );
});

$container->set(UpdateService::class, function (Container $container) {
    return new LocalUpdateService(
        $container->get(ArticleRepository::class)
    );
});

$container->set(DeleteService::class, function (Container $container) {
    return new LocalDeleteService(
        $container->get(ArticleRepository::class)
    );
});

$container->set(GetAllService::class, function (Container $container) {
    return new LocalGetAllService(
        $container->get(ArticleRepository::class)
    );
});

$container->set(GetByIdService::class, function (Container $container) {
    return new LocalGetByIdService(
        $container->get(ArticleRepository::class)
    );
});

$container->set(CreateController::class, function (Container $container) {
    return new CreateController(
        $container->get(CreateService::class),
        $container->get(LogService::class)
    );
});

$container->set(DeleteController::class, function (Container $container) {
    return new DeleteController(
        $container->get(DeleteService::class),
        $container->get(LogService::class)
    );
});

$container->set(UpdateController::class, function (Container $container) {
    return new UpdateController(
        $container->get(UpdateService::class),
        $container->get(LogService::class)
    );
});

$container->set(GetAllController::class, function (Container $container) {
    return new GetAllController(
        $container->get(GetAllService::class),
        $container->get(LogService::class)
    );
});

$container->set(GetByIdController::class, function (Container $container) {
    return new GetByIdController(
        $container->get(GetByIdService::class),
        $container->get(LogService::class)
    );
});

$container->set(CreateFormController::class, function (Container $container) {
    return new CreateFormController(
        $container->get(LogService::class)
    );
});

$container->set(UpdateFormController::class, function (Container $container) {
    return new UpdateFormController(
        $container->get(GetByIdService::class),
        $container->get(LogService::class)
    );
});


$container->set('routes', require __DIR__ . '/routes.php');

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

        $controllerClass = "\\ArticleApp\\Controllers\\{$controllerName}";
        $controllerInstance = $container->get($controllerClass);

        $request = Request::createFromGlobals();

        $result = $controllerInstance($request, $vars);

        handleResponse($twig, $result);
        break;
}