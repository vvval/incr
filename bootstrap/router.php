<?php

declare(strict_types=1);

use App\Application\Controllers;
use Laminas\Diactoros\Response\RedirectResponse;
use League\Route\Router;

/**
 * @var Router $router
 */
$router->get(
    '/',
    static fn() => new RedirectResponse('/swagger/'),
);
$router->put(
    '/admin/team/{teamId:number}',
    [Controllers\Admin::class, 'addTeam'],
);
$router->delete(
    '/admin/team/{teamId:number}',
    [Controllers\Admin::class, 'deleteTeam'],
);
$router->put(
    '/admin/team/{teamId:number}/counters/{userId:number}',
    [Controllers\Admin::class, 'addCounter'],
);
$router->delete(
    '/admin/team/{teamId:number}/counters/{userId:number}',
    [Controllers\Admin::class, 'deleteCounter'],
);

$router->get(
    '/teams',
    [Controllers\Team::class, 'get'],
);
$router->get(
    '/team/{teamId:number}/counters/total',
    [Controllers\Team::class, 'getTotalCounter'],
);
$router->get(
    '/team/{teamId:number}/counters',
    [Controllers\Team::class, 'getCounters'],
);

$router->put(
    '/team/{teamId:number}/counters/{userId:number}',
    [Controllers\User::class, 'addCounter'],
);
$router->post(
    '/team/{teamId:number}/counters/{userId:number}',
    [Controllers\User::class, 'incrementCounter'],
);

return $router;
