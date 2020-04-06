<?php

require __DIR__ . '/vendor/autoload.php';

use App\Kernel;
use Symfony\Component\HttpFoundation\Request;

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$request = Request::createFromGlobals();
$response = (new Kernel())->respond($request);
$response->send();
