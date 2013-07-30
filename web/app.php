<?php

use Symfony\Component\ClassLoader\ApcClassLoader;
use Symfony\Component\HttpFoundation\Request;

$loader = require_once __DIR__ . '/../app/bootstrap.php.cache';

$loader = new ApcClassLoader('dashboard', $loader);
$loader->register(true);

require_once __DIR__ . '/../app/AppKernel.php';

$kernel = new AppKernel('prod', false);
$kernel->loadClassCache();

Request::setTrustedProxies(['127.0.0.1']);
$request  = Request::createFromGlobals();

$response = $kernel->handle($request);
$response->send();

$kernel->terminate($request, $response);