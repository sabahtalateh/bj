<?php

use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

$routes = new RouteCollection();

$routes->add('tasks', new Route('/', [
    '_controller' => \App\Controller\TasksController::class,
    '_action' => 'list'
]));

$routes->add('add_task', new Route('/add', [
    '_controller' => \App\Controller\TasksController::class,
    '_action' => 'add'
]));

$routes->add('edit_task', new Route('/edit/{id}', [
    '_controller' => \App\Controller\TasksController::class,
    '_action' => 'edit'
]));

$routes->add('toggle_task', new Route('/toggle/{id}', [
    '_controller' => \App\Controller\TasksController::class,
    '_action' => 'toggle'
]));

$routes->add('login', new Route('/login', [
    '_controller' => \App\Controller\LoginController::class,
    '_action' => 'login'
]));

$routes->add('logout', new Route('/logout', [
    '_controller' => \App\Controller\LoginController::class,
    '_action' => 'logout'
]));

return $routes;
