<?php

namespace App\config;

use App\Service\SecurityService;
use App\Service\TaskService;
use App\Service\UserService;
use Symfony\Component\DependencyInjection\ContainerBuilder;

$appConfig = require(__DIR__ . '/app.php');

const TASK_SERVICE = 'service.task';
const USER_SERVICE = 'service.user';
const SECURITY_SERVICE = 'service.security';

$containerBuilder = new ContainerBuilder();
$containerBuilder->register(TASK_SERVICE, TaskService::class);
$containerBuilder->register(USER_SERVICE, UserService::class);
$containerBuilder->register(SECURITY_SERVICE, SecurityService::class)
    ->addArgument($appConfig)
    ->addArgument($containerBuilder->get(USER_SERVICE));

return $containerBuilder;
