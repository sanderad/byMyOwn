<?php

use App\App;
use App\Config;
use App\Controllers\HomeController;
use App\Controllers\TransactionsController;
use App\Exceptions\RouteNotFoundException;
use App\router;


require __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

define('STORAGE_PATH', __DIR__ . '/../storage');
define('VIEW_PATH', __DIR__ . '/../views');


$router = new router();

$router->get('/', [HomeController::class, 'index'])
       ->get('/transactions', [TransactionsController::class, 'sendTransaction'])
       ->post('/transactions/tables', [TransactionsController::class, 'showTransaction'])
       ->post('/transactions/table', [TransactionsController::class, 'show']);


(new App($router,
       ['uri' => $_SERVER['REQUEST_URI'], 'method' => $_SERVER['REQUEST_METHOD']],
       new Config($_ENV)
))->run(); 