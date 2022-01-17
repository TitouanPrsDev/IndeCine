<?php

namespace App\Core;

use App\Controllers\MainController;
use App\Controllers\MoviesController;

class Router {
    public function start () {
        session_start();
        require_once 'src/Entities/functions.php';

        $uri = $_SERVER['REQUEST_URI'];

        if ($uri !== '/' && $uri[-1] === '/') {
            $uri = substr($uri, 0, -1);

            http_response_code(301);
            header("Location: " . $uri);
        }

        $params = explode('/', $_GET['p']);

        if ($params[0] !== '') {
            if ($params[0] === 'movies' && isset($params[1])) {
                $controller = new MoviesController();
                array_shift($params);
                $controller -> movie($params);

            } else {
                $controller = '\\App\\Controllers\\' . ucfirst(array_shift($params)) . 'Controller';
                $controller = new $controller();

                $method = (isset($params[0])) ? array_shift($params) : 'index';
                if (method_exists($controller, $method)) (isset($params[0])) ? $controller -> $method($params) : $controller -> $method();
            }
        } else {
            $controller = new MainController();
            $controller -> index();
        }
    }
}
