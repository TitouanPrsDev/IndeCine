<?php

use App\Autoloader;
use App\Core\Router;

define("ROOT", dirname(__DIR__) . '/webdev/src');

require_once ROOT . '/Autoloader.php';
Autoloader::register();

$app = new Router;
$app -> start();