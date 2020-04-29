<?php

use Symfony\Component\Routing;

$routes = new Routing\RouteCollection();
$routes->add('index', new Routing\Route('/', [
    '_controller' => 'App\Controller\TaskController::index',
]));
$routes->add('addTask', new Routing\Route('/addTask', [
    '_controller' => 'App\Controller\TaskController::addTask',
]));
$routes->add('login', new Routing\Route('/login', [
    '_controller' => 'App\Controller\AdminController::index',
]));

return $routes;
