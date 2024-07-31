<?php

    require '../vendor/autoload.php';

    $container = new DI\Container();

    $container->__construct([
        League\Plates\Engine::class => function() {
            return new League\Plates\Engine('../app/views');
        },

        Delight\Auth\Auth::class => function($container) {
            return new Delight\Auth\Auth($container->get('PDO'));
        },

        Aura\SqlQuery\QueryFactory::class => function() {
            return new Aura\SqlQuery\QueryFactory('mysql');
        },

        PDO::class => function() {
            $driver = 'mysql';
            $host = 'localhost';
            $database = 'home_project';
            $username = 'root';
            $password = 'root';

            return new PDO("$driver:host=$host; dbname=$database", $username, $password);
        },
    ]);

    $dispatcher = FastRoute\simpleDispatcher(function(FastRoute\RouteCollector $r) {
        $r->addRoute('GET', '/page_register', ['App\auth\RegisterController', 'page_register']);
        $r->addRoute('POST', '/page_register', ['App\auth\RegisterController', 'page_register']);
        $r->addRoute('GET', '/verification_mail', ['App\auth\VerificationEmailController', 'verification_mail']);
        $r->addRoute('GET', '/verification_mail2', ['App\auth\VerificationEmailController', 'verification_mail2']);
        $r->addRoute('GET', '/page_login', ['App\auth\LoginController', 'page_login']);
        $r->addRoute('POST', '/page_login', ['App\auth\LoginController', 'page_login']);
        $r->addRoute('GET', '/users', ['App\HomeController', 'users']);
        $r->addRoute('GET', '/logOut', ['App\HomeController', 'logOut']);
        $r->addRoute('GET', '/edit', ['App\HomeController', 'edit']);
        $r->addRoute('POST', '/edit', ['App\HomeController', 'edit']);
        $r->addRoute('GET', '/security', ['App\HomeController', 'security']);
        $r->addRoute('POST', '/security', ['App\HomeController', 'security']);
        $r->addRoute('GET', '/status', ['App\HomeController', 'status']);
        $r->addRoute('POST', '/status', ['App\HomeController', 'status']);
        $r->addRoute('GET', '/media', ['App\HomeController', 'media']);
        $r->addRoute('POST', '/media', ['App\HomeController', 'media']);
        $r->addRoute('GET', '/delete_user', ['App\HomeController', 'delete_user']);
        $r->addRoute('GET', '/create_user', ['App\HomeController', 'create_user']);
        $r->addRoute('POST', '/create_user', ['App\HomeController', 'create_user']);
    });

    $httpMethod = $_SERVER['REQUEST_METHOD'];
    $uri = $_SERVER['REQUEST_URI'];

    if (false !== $pos = strpos($uri, '?')) {
        $uri = substr($uri, 0, $pos);
    }
    $uri = rawurldecode($uri);

    $routeInfo = $dispatcher->dispatch($httpMethod, $uri);
    switch ($routeInfo[0]) {
        case FastRoute\Dispatcher::NOT_FOUND:
            echo 'Error 404';
        break;
        case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
            $allowedMethods = $routeInfo[1];
            echo 'Error 405';
        break;
        case FastRoute\Dispatcher::FOUND:
            $handler = $routeInfo[1];
            $vars = $routeInfo[2];
            $container->call($handler, $vars);
        break;
    }
?>