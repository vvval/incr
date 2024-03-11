<?php

declare(strict_types=1);

use App\Application;
use Laminas\Diactoros\ServerRequestFactory;
use Laminas\HttpHandlerRunner\Emitter\SapiEmitter;

error_reporting(E_ALL);
require dirname(__DIR__) . '/vendor/autoload.php';

$request = ServerRequestFactory::fromGlobals($_SERVER, $_GET, $_POST, $_COOKIE, $_FILES);
$response = (new Application())->handle($request);

(new SapiEmitter())->emit($response);
