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
$routes->add('admin', new Routing\Route('/admin', [
    '_controller' => 'App\Controller\AdminController::edit',
]));
$routes->add('logout', new Routing\Route('/logout', [
    '_controller' => 'App\Controller\AdminController::logout',
]));
$routes->add('update', new Routing\Route('/update', [
    '_controller' => 'App\Controller\AdminController::update',
]));

return $routes;
